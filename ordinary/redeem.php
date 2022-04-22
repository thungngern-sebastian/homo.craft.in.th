<?php

if(!empty($_P['RD_id'])){
    $l=[];
    $query = _que('SELECT * FROM redeem_code WHERE code = ?',[$_P['RD_id']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $l = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(!empty($l)){
        
        $query = _que('SELECT * FROM topup_history WHERE ref1 = ?',[$_P['RD_id']]);
        if(!is_array($query) || @!isset($query['failed'])){
            $x = $query->fetch(PDO::FETCH_ASSOC);
        }
        if(empty($x)){
            $status='Success';
            $amount=$l['amount'];
            $ref1=$_P['RD_id'];
        }
    }
}