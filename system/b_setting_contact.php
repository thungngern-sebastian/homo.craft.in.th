<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_P['address_th']) && !empty($_P['address_en']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){

            $query = _que('UPDATE setting SET address_th = ?, address_en = ?', 
            [$_P['address_th'],$_P['address_en']]);
            if(!is_array($query) || @!isset($query['failed'])){
              http_response_code(200);
              $data = ['msg' => 'Done!'];
            } else {
              $data = ['msg' => $query['msg']];
            }

          }
        }
    } else {
        unset($_SESSION["username"]);
    }
    
} else {
    $data=['msg'=>'data incorrect'];
}