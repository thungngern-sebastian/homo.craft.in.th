<?php
if(!empty($_P['ip1']) && !empty($_P['ip2']) &&!empty($_P['ip3']) &&!empty($_P['ip4']) &&!empty($_P['ip5']) && !empty($_SESSION["username"])){
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
              for ($x=$_P['ip4']; $x <= $_P['ip5']; $x++) {
                _que('INSERT INTO ip_address (ipv4,subnet,boardcast,submark,gateway,available) VALUES (?,?,?,?,?,?)', 
                [$_P['ip1'].'.'.$_P['ip2'].'.'.$_P['ip3'].'.'.$x,$_P['subnet'],$_P['boardcast'],$_P['submark'],$_P['gateway'],$pp]);
              }
              http_response_code(200);
              $data = ['msg' => 'Done!','eval'=>'location.reload()'];
          }
        }
    } else {
        unset($_SESSION["username"]);
    }
}