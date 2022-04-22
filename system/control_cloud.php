<?php

if(!empty($_SESSION['username']) && !empty($_P['ref']) && !empty($_P['action'])){
    $cloud=[];
    $query = _que('SELECT * FROM vm 
    INNER JOIN customer ON vm.cusid = customer.id
    INNER JOIN hosts ON vm.host = hosts.host WHERE vm.cusid=? AND vm.ref=?',[$_SESSION['username'],$_P['ref']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $cloud = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(empty($cloud)){
        $data=['msg'=>'No cloud in system'];
    } elseif($cloud['manitance']==1) {
        $data=['msg'=>'เครื่องอยู่ในช่วงปรับปรุง'];
    } elseif($cloud['pause']==1) {
        $data=['msg'=>'หมดอายุ'];
    } else {
            require_once'../API/Xen.php';
            $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
            $key = PsXenAPI::apref($_P['ref']);
            switch ($_P['action']) {
                case 'shutdown':  
                    $data=$xen->rq('VM.clean_shutdown', [$key]);
                    break;
                case 'restart':
                    $data=$xen->rq('VM.clean_reboot', [$key]);
                    break;
                case 'forceshutdown':
                    $data=$xen->rq('VM.hard_shutdown', [$key]);
                    break;
                case 'forcerestart':
                    $data=$xen->rq('VM.hard_reboot', [$key]);
                    break;
                case 'start':
                    $data=$xen->rq('VM.start', [$key,false,true]);
                    break;
            }
            if($data['Status']!='Success'){
                $data = ['msg' => 'ไม่สำเร็จ '.@$data['ErrorDescription'][0]];
            } else {
                http_response_code(200);
                $data = ['msg' => 'สำเร็จ','eval'=>'location.reload()'];
            }
        
    }
} else{
    $data=['msg'=>'Data incorrect!'];
}
