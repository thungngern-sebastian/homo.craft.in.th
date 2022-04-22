<?php
if(!empty($_P['ref']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){
              $pp = (isset($_P['pause']))?1:0;
              $ppx = (isset($_P['unlimited']))?1:0;
              $query = _que('REPLACE INTO vm (ref, user_label, cusid, timestamp, plan,lenght,cpu,ram,disk,host,base_price,pause,template,os,distro,user_password,unlimited) VALUES 
              (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [$_P['ref'],$_P['user_label'],$_P['cusid'],$_P['timestamp'],$_P['plan'],$_P['lenght']
              ,$_P['cpu'],$_P['ram'],$_P['disk'],$_P['host'],$_P['base_price'],$pp,$_P['template'],$_P['os'],$_P['distro'],$_P['user_password'],$ppx]);
              if(!is_array($query) || @!isset($query['failed'])){
                http_response_code(200);
                $data = ['msg' => 'Done!','eval'=>'window.location.replace("?page=?page=b_vm&ref='.$_P['ref'].'&h='.$_P['host'].'")'];
              } else {
                $data = ['msg' => $query['msg']];
              }
          }
        }
    } else {
        unset($_SESSION["username"]);
    }
}