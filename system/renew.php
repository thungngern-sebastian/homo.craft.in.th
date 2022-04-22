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
            $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
            $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
            
            if(!$is_suspended && $is_activated){
                $query = _que('SELECT * FROM vm
                INNER JOIN hosts ON vm.host = hosts.host 
                INNER JOIN ip_address ON vm.ref = ip_address.uuid WHERE vm.cusid=? AND vm.ref=?',[$_SESSION['username'],$_P['ref']]);
                if(!is_array($query) || @!isset($query['failed'])){
                  $cloud = $query->fetch(PDO::FETCH_ASSOC);
                  if(!empty($cloud)){
                    $price=$cloud['lenght']*$cloud['base_price'];
                    if($user['point']>=$price){
                        @require_once "../API/Xen.php";
                        $date = strtotime($cloud['timestamp']." +".strval($cloud['lenght'])." day");

                        $date = date('Y-m-d H:i:s',$date);

                        $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
                        $ref= $xen->apref($cloud['ref']);
                        @$xen->rq('VM.set_name_label',[$ref,$cloud['ipv4'].' - '.$user['email'].' - '.$date]);
                        _que('UPDATE vm SET pause=0,timestamp=DATE_ADD(timestamp, INTERVAL '.$cloud['lenght'].' DAY) WHERE ref=?;UPDATE customer SET point=point-? WHERE id=?', [$cloud['ref'],$price,$user['id']]);
                        _que('INSERT INTO log (data) VALUES (?)',["RENEW '{$_P['ref']}' BY USER"]);
                        http_response_code(200);
                        $data = ['msg' => 'ต่ออายุสำเร็จ','eval'=>'location.reload()'];
                    } else {
                        $data = ['msg' => 'พ้อยไม่เพียงพอ'];
                    }
                  }
                } else {
                  $data = ['msg' => $query['msg']];
                }
            }
        }
    } else {
      $data = ['msg' => $query['msg']];
    }
} else {
  $data = ['msg' => 'Data inccorect!'];
}