<?php

if(!empty($_SESSION['username']) && !empty($_P['thid']) && is_numeric($_P['thid']) && !empty($_P['province'])){
    $cloud=[];
    $query = _que('SELECT * FROM customer where id',[$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
            unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
        $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
        
        if(!$is_suspended && empty($user['id_card'])){
            if(ThaiIDCheckSum($_P['thid'])){
                
                $province= json_decode(file_get_contents('../data/provinces.json'),true);
                $val = array_search($_P['province'], array_column($province, 'enName'));
                if(!empty($val)){
                    if($province[$val]['code'] == substr($_P['thid'], 1, 2)){
                        
                        $query = _que('UPDATE customer SET id_card=? WHERE id=?',[$_P['thid'],$_SESSION['username']]);
                        if(!is_array($query) || @!isset($query['failed'])){
                            http_response_code(200);
                            $data=['msg'=>'Success','eval'=>'location.reload()'];
                        } else {
                            $data=['msg'=>$query['msg']];

                        }
                    } else {
                        $data=['msg'=>'Data incorrect!'];
                    }
                } else {
                    $data=['msg'=>'Data incorrect!'];
                }
            } else{
                $data=['msg'=>'Data incorrect!'];
            }
        } else {
            $data=['msg'=>'Nor you cant'];
            
        }
    }
}else {
    $data=['msg'=>$query['msg']];

}
} else{
    $data=['msg'=>'Data incorrect!'];
}
