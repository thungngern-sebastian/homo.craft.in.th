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
            $query = _que('SELECT customer.id,customer.email,hosts.*,hosts.username as hostusername,vm.*,ip_address.* FROM vm 
            INNER JOIN hosts ON vm.host = hosts.host
            INNER JOIN ip_address ON vm.ref = ip_address.uuid
            INNER JOIN customer ON vm.cusid = customer.id
            WHERE vm.ref = ?', [$_P['ref']]);
                  if(!is_array($query) || @!isset($query['failed'])){
                      $cloud = $query->fetch(PDO::FETCH_ASSOC);
                      if(!empty($cloud)){
                          require_once'../API/Xen.php';
                          $xen = new PsXenAPI($cloud['host'],$cloud['hostusername'],$cloud['password']);
                          $key = PsXenAPI::apref($_P['ref']);
                          $name= $cloud['ipv4'].' - '.$cloud['email'].' - '.$cloud['timestamp'];
                          $s = $xen->rq('VM.set_name_label',[$key,$name]);
  
                          if($s['Status']!='Success'){
                              $data = ['msg' => 'ไม่สำเร็จ TwT '.$s['ErrorDescription'][0]];
                          } else {
                              http_response_code(200);
                              $data = ['msg' => 'สำเร็จ','eval'=>'location.reload()'];
                          }
                          
                      } else {
                          $data=['msg'=>'No VM pls check ip address ref'];
  
                      }
                  } else {
                      $data=['msg'=>$query['msg']];
                  }

          }
        }
    } else {
        unset($_SESSION["username"]);
    }
}