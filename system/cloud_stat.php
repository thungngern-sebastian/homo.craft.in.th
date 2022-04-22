<?php

if(!empty($_SESSION['username']) && !empty($_G['ref'])){
    $cloud=[];
    $query = _que('SELECT * FROM vm
    INNER JOIN customer ON vm.cusid = customer.id
    INNER JOIN hosts ON vm.host = hosts.host WHERE vm.cusid=? AND vm.ref=?',[$_SESSION['username'],$_G['ref']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $cloud = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(empty($cloud)){
        $data=['msg'=>'No cloud in system'];
    } else {
        require_once'../API/Xen.php';
        $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
        $key = PsXenAPI::apref($_G['ref']);
        http_response_code(200);
        $hosts['status']=false;
        if(!is_null($xen->id_session)){
            $hosts['status']=true;
            $host = @$xen->rq('VM.query_data_source',[$key,'cpu_avg']);
            $hosts['cpu']= round((($host['Status']!='Success')?@$xen->rq('VM.query_data_source',[$key,'cpu0'])['Value']:$host['Value'])*100,2);
            $hosts['ram']['max'] = @$xen->rq('VM.query_data_source',[$key,'memory'])['Value'];
            $hosts['ram']['free'] = @$xen->rq('VM.query_data_source',[$key,'memory_internal_free'])['Value']*1000;
            $hosts['ram']['used'] = $hosts['ram']['max']-$hosts['ram']['free'];
            $hosts['ram']['percent'] = round(($hosts['ram']['used']/$hosts['ram']['max'])*100,2);
            $hosts['ram']['max']=@PsXenAPI::B2S($hosts['ram']['max']);
            $hosts['ram']['used']=@PsXenAPI::B2S($hosts['ram']['used']);
            $hosts['ram']['free']=@PsXenAPI::B2S($hosts['ram']['free']);
            $up=@$xen->rq('VM.query_data_source',[$key,'vif_0_tx'])['Value'];
            $down=@$xen->rq('VM.query_data_source',[$key,'vif_0_rx'])['Value'];
            $hosts['net']['up_per']=round(($up/128000000)*100,2);
            $hosts['net']['down_per']=round(($down/128000000)*100,2);
            $hosts['net']['up']=($up==0)?'0 B':@PsXenAPI::B2S(@$up);
            $hosts['net']['down']=($down==0)?'0 B':@PsXenAPI::B2S(@$down);
        }
        $data=$hosts;
    }
} else{
    $data=['msg'=>'Data incorrect!'];
}
