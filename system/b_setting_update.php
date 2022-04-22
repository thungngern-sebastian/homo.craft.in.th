<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_P['icon']) && !empty($_P['logo']) && !empty($_P['logo_footer']) && !empty($_P['bg']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){

            $query = _que('UPDATE setting SET icon= ?, logo = ?, logo_footer = ?, bg = ?', 
            [$_P['icon'],$_P['logo'],$_P['logo_footer'],$_P['bg'],]);
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