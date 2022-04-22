<?php
if(!empty($_P['id']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){
            $pp = (isset($_P['banned']))?1:0;
              $query = _que('UPDATE customer SET fname=?,lname=?,email=?,point=?,id_card=?,phone=?,suspended=?,fbid=? WHERE id=?', 
              [$_P['fname'],$_P['lname'],$_P['email'],$_P['point'],$_P['thid'],$_P['phone'],$pp,$_P['fbid'],$_P['id']]);
              if(!is_array($query) || @!isset($query['failed'])){
                http_response_code(200);
                $data = ['msg' => 'Done!','eval'=>'location.reload()'];
              } else {
                $data = ['msg' => $query['msg']];
              }
          }
        }
    } else {
        unset($_SESSION["username"]);
    }
}