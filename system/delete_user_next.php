<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_SESSION["username"])){
  if(hcap_check($_P['h-captcha-response'])){
    require_once'../API/Validator.php';
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
      $user = $query->fetch(PDO::FETCH_ASSOC);
      if(empty($user)){
        unset($_SESSION["username"]);
      } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
        
        $v = new Valitron\Validator($_P,[],$_LANG,'Vaild_lang');
        
        $v->rule('required', ['confirm_password']);

        $setting=[];
        $setting = _que('SELECT * FROM `setting`');
        if(!is_array($setting) || @!isset($setting['failed'])){
          $setting = $setting->fetchAll(PDO::FETCH_ASSOC);
        }

        $tmp = explode('$', $user['password']);
        if (hash('sha256', hash('sha256',$_P['confirm_password']) . $tmp[2]) == $tmp[3]) {
          if($user['status'] == True){
            $status = FALSE;
          }else{
            $status = TRUE;
          }
          
          $query = _que('UPDATE customer SET status = ? where id=?', 
          [$status,$_SESSION["username"]]);
          if(!is_array($query) || @!isset($query['failed'])){
            http_response_code(200);
            unset($_SESSION["username"]);
            $data = ['msg' => 'Done!','eval'=>'window.location.replace("?page=home")'];
          } else {
            $data = ['msg' => $query['msg']];
          }
        } else {
            $data = ['msg' => L::data_incorrect];
        }
      }
    } else {
      unset($_SESSION["username"]);
    }
  } else {
    $data = ['msg' =>'Captcha ไม่ถูกต้อง'];
  }
    
} else {
  $data=['msg'=>'data incorrect'];
}