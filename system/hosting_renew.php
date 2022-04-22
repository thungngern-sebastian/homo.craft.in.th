<?php
if(!empty($_SESSION['username']) && !empty($_P['id']) && is_numeric($_P['id']) & $_P['id'] > 0) {
    $pdo = _conn();
    $query = _que('SELECT * FROM `customer` where `id` = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)) {
            unset($_SESSION["username"]);
        } elseif (empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
            $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
            $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
            $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
        
            if(!$is_suspended && $is_activated) {
                $query = _que('SELECT * FROM `hosting` WHERE `cusid` = ? AND `id` = ?', [$_SESSION['username'], $_P['id']]);
                if(!is_array($query) || @!isset($query['failed'])) {
                    $hosting = $query->fetch(PDO::FETCH_ASSOC);
                    if(!empty($hosting)) {
                        if($user['point'] >= $hosting['base_price']) {
                            _que('UPDATE `hosting` SET `expiration_date` = DATE_ADD(`expiration_date`, INTERVAL '.$hosting['duration'].' DAY) WHERE `id` = ?;', [
                                $hosting['id']
                            ]);
    
                            _que('UPDATE `customer` SET `point` = `point` - ? WHERE `id` = ?',[$hosting['base_price'], $user["id"]]);

                            $order_ref = hash('sha256', (microtime() . rand()) . $_SESSION["username"] . $hosting['def_domain']);
                            $info = 'Renew Hosting '.$hosting['def_domain'].' '.$hosting['duration'].' Day';
                            _que('INSERT INTO `order_history` (`ref`, `cusid`, `price`, `info`) values (?, ?, ?, ?);', [
                                $order_ref, $user["id"], $hosting['base_price'], $info
                            ]);

                            http_response_code(200);
                            $data = ['msg' => 'ต่ออายุ Hosting สำเร็จ', 'eval'=>'location.reload()'];
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