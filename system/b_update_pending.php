<?php
if(!empty($_P['ref']) && !empty($_P['stat']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){
              if($_P['stat']=='Success'){
                $query = _que('UPDATE topup_history SET status=? WHERE ref1=?; UPDATE customer SET point=point+? WHERE id=?', ['Success',$_P['ref'],$_P['am'],$_P['uid']]);
              } else {
                $query = _que('UPDATE topup_history SET status=? WHERE ref1=?', ['Failed',$_P['ref']]);
              }
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