<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_P["host"]) && !empty($_P["user"]) && !empty($_P["pass"]) && !empty($_P["mail"]) && !empty($_P["mail_title"]) && !empty($_SESSION["username"])){
  $pdo = _conn();
  $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
  if(!is_array($query) || @!isset($query['failed'])){
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if(empty($user)){
      unset($_SESSION["username"]);
    } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
      $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
      if($is_admin){
        $setting=[];
        $setting = _que('SELECT * FROM `setting`');
        if(!is_array($setting) || @!isset($setting['failed'])){
          $setting = $setting->fetchAll(PDO::FETCH_ASSOC);
        }
        $json = json_encode(
          array_replace(
            (array)array(
              'host' => $_P['host'],
              'username' => $_P['user'],
              'password' => $_P['pass'],
              'mail' => $_P['mail'],
              'title' => $_P['mail_title']
            )
          )
        );
        
        $query = _que('UPDATE setting SET stmp = ?', 
        [$json]);
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