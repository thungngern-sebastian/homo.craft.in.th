<?php
if(!empty($_SESSION['username'])){
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION['username']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)){
            http_response_code(200);
            $data=['point'=>number_format($user['point'], 2)];
        }
    }
}