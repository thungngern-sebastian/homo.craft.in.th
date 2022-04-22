<?php
if(!empty($_SESSION['username'])){
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)){
            $tmp = explode('$', $user['password']);
            if (hash('sha256', hash('sha256',$_P['password']) . $tmp[2]) == $tmp[3]) {
                $_SESSION['sc_check']=true;
                http_response_code(200);
                $data=['msg' => 'สำเร็จ','eval'=>'window.location.reload()'];
            } else {
                $data = ['msg' => L::data_incorrect];
            }
        }
    }
}