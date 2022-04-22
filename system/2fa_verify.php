<?php
if(!empty($_SESSION['username']) && !empty($_P['2fa']) && empty($_SESSION['2fa'])  ){
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)){
            if(!empty($user['2fa'])){  
                require_once '../API/googleauten.php';
                $ga = new PHPGangsta_GoogleAuthenticator();
                    $checkResult = $ga->verifyCode($user['2fa'],$_P['2fa'], 2);
                    if ($checkResult) {
                        $_SESSION['2fa']=true;
                        http_response_code(200);
                        $data=['msg' => 'สำเร็จ','eval'=>'window.location.replace("?page=home")'];
                    } else {
                        $data = ['msg' => L::data_incorrect];
                    }
            }
        }
    }
} else {
    $data=['msg' => 'ข้อมูลไม่ถูกต้อง'];
}