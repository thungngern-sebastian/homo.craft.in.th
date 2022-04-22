<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_SESSION["username"])){
  if(hcap_check($_P['h-captcha-response'])){
    http_response_code(200);
    $data = ['msg' => 'Done!','eval'=>'window.location.replace("?page=delete_user")'];
  } else {
    $data = ['msg' =>'Captcha ไม่ถูกต้อง'];
  }
    
} else {
  $data=['msg'=>'data incorrect'];
}