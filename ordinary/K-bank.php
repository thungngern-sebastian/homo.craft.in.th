<?php

if(@is_numeric($_P['KB_hour']) && @is_numeric($_P['KB_min'])){
    require_once '../API/KasikornBank.class.php';
    $kbank = new KasikornBank("amornchza", "Amorn0292474745", "../cookie.txt");
    if (!$kbank->CheckSession()) {
        $kbank->Login();
    }
    $ref1=md5($_P['KB_hour'].'-'.$_P['KB_min'].'-'.$_P['amount']);
    $TodayStatement = $kbank->GetTodayStatement("065-3-38424-6");
    $l=[];
    $query = _que('SELECT * FROM topup_history WHERE ref1 = ?',[$ref1]);
    if(!is_array($query) || @!isset($query['failed'])){
        $l = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(empty($l)){
        foreach($TodayStatement as $v){
        if(empty($v['Deposit (THB)'])){
            continue;
        }
        if(floor(str_replace(',', '', $v["Deposit (THB)"])) ==$_P['amount']){
            $data = explode(" ",$v["Date/Time"]);
                $data1 = explode(":",$data[1]);
                if($data1[0] == $_P['KB_hour'] && $data1[1] == $_P['KB_min']){
                    $status='Success';
                    break;
                }
            }
        }
    }
}