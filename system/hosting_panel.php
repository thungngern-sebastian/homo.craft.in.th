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
                        require_once ('../API/PleskAPI.php');
                        $plesk = new kiznick_Plesk_API('https://hosting.drite.in.th:8443/', 'admin', 'limited3AB@@#');
                        $login = $plesk->request('POST', 'cli/admin/call', [
                            'params' => [
                                "--get-login-link",
                                "-user",
                                $hosting['username']
                            ]
                        ]);
                        if($login['code'] == 200 && $login['body']['code'] == 0) {
                            http_response_code(200);
                            $data = ['msg' => 'ดำเนินการสำเร็จ', 'eval' => 'window.open("'.$login['body']['stdout'].'");'];
                        } else {
                            $data = ['msg' => 'ไม่สามารถสร้างลิ่งก์สำหรับการเข้าสู่ระบบได้ในขณะนี้ !'];
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