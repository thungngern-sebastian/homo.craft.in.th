<?php
if(!empty($_P['id']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM `customer` where `id` = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)) {
            unset($_SESSION["username"]);
        } else if(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
            $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
            $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
            $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
            
            if(!$is_suspended && $is_activated) {
                $query = _que('UPDATE `hosting` SET `autorenew` = ? WHERE `id` = ? AND `cusid` = ?', [(isset($_P['c'])) ? 1 : 0, $_P['id'], $_SESSION["username"]]);
                if(!is_array($query) || @!isset($query['failed'])) {
                    http_response_code(200);
                    $data = ['msg' => 'สำเร็จ!','eval'=>'location.reload()'];
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