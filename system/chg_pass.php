<?php

if(!empty($_SESSION['username'])){
    require_once'../API/Validator.php';
    
    $_LANG=(!empty($_SESSION['lang']) && $_SESSION['lang']=='th')?'th':'en';
    $v = new Valitron\Validator($_P,[],$_LANG,'Vaild_lang');
    
    $v->rule('required', ['old_password', 'new_password', 'confirm_password']);
    $v->rule('lengthMin', 'new_password', 6);
    $v->rule('equals', 'confirm_password', 'new_password');
    
    if($v->validate()) {
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
            $data = ['msg' => L::data_incorrect];
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
            $tmp = explode('$', $user['password']);
            if (hash('sha256', hash('sha256',$_P['old_password']) . $tmp[2]) == $tmp[3]) {
                $salt = _ranstr();
                $hash = hash('sha256', hash('sha256', $_P['new_password']) . $salt);
                $pass = htmlspecialchars(strip_tags("\$SHA\${$salt}\${$hash}"));
                $query = _que('UPDATE customer SET password=? where id = ?', [$pass,$_SESSION['username']]);
                if(!is_array($query) || @!isset($query['failed'])){
                    http_response_code(200);
                    $data = ['msg' => 'Change password '.L::complete];
                } else {
                    $data = ['msg' =>$query['msg']];
                }
            } else {
                $data = ['msg' => L::data_incorrect];
            }
        }
    } else {
        $data = ['msg' =>$query['msg']];
    }
    } else {   
    $data=['msg'=>array_values($v->errors())[0][0]];
    }
}
