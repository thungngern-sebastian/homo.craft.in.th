<?php

if(!empty($_SESSION['username']) && !empty($_P['ref'])){
    $cloud=[];
    $query = _que('SELECT * FROM vm 
    INNER JOIN customer ON vm.cusid = customer.id
    INNER JOIN hosts ON vm.host = hosts.host 
    INNER JOIN ip_address ON vm.ref = ip_address.uuid WHERE vm.cusid=? AND vm.ref=?',[$_SESSION['username'],$_P['ref']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $cloud = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(empty($cloud)){
        $data=['msg'=>'No cloud in system'];
    } elseif($cloud['manitance']==1) {
        $data=['msg'=>'เครื่องอยู่ในช่วงปรับปรุง'];
    } elseif($cloud['pause']==1) {
        $data=['msg'=>'หมดอายุ'];
    } else
    if(empty($cloud['2fa']) || (!empty($cloud['2fa']) && !empty($_SESSION['2fa']))){
            require_once'../API/Xen.php';
            $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
            $key = PsXenAPI::apref($_P['ref']);
            $name= $cloud['ipv4'].' - '.$cloud['email'].' - '.$cloud['timestamp'];
            @$xen->rq('VM.set_name_label',[$key,$name]);

            $VM=$xen->rq('VM.get_record', [$key]);
            $VMV = $VM['Value'];
            $set_ip=$xen->rq('VIF.configure_ipv4', [$VMV['VIFs'][0],'Static',$cloud['ipv4'].'/'.$cloud['subnet'],$cloud['gateway']]);   
		
		  if($set_ip['Status']!='Success'){
			  $xenstore = [
				  "vm-data/ipv4" => $cloud['ipv4'],
				  "vm-data/subnet" => $cloud['subnet'],
				  "vm-data/gateway" => $cloud['gateway'],
				  "vm-data/submark" => $cloud['subnet']
			  ];
			  $set_xenstore_data = $xen->rq('VM.set_xenstore_data', [$key, $xenstore]);
			  http_response_code(200);
			  $data = ['msg' => 'สำเร็จ'];
		  } else {
             http_response_code(200);
             $data = ['msg' => 'สำเร็จ'];
           }
        
    }
} else{
    $data=['msg'=>'Data incorrect!'];
}
