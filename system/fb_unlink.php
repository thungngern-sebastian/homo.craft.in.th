<?php
if(!empty($_SESSION['username'])){
    $query = _que('UPDATE customer SET fbid="" WHERE id=?', [$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])){
        http_response_code(200);
        $data = ['msg' => L::complete,'eval'=>'window.location.reload()'];
    } else {
        $data = ['msg' =>$query['msg']];
    }
}
