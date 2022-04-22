<?php
if(!empty($_P['ipv4']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){
            $pp = (isset($_P['available']))?1:0;
              $query = _que('REPLACE INTO ip_address (ipv4,subnet,boardcast,submark,gateway,uuid,available) VALUES (?,?,?,?,?,?,?)', 
              [$_P['ipv4'],$_P['subnet'],$_P['boardcast'],$_P['submark'],$_P['gateway'],$_P['uuid'],$pp]);
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