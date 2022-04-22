<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_P['name']) && !empty($_P['title']) && !empty($_P['editor-markdown-doc']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){
            $header=json_encode([
                'title'=>$_P['title'],
                'icon'=> $_P['icon'],
                'date'=> date("Y-m-d H:i:s")
            ]);
            $markdown=$_P['editor-markdown-doc'];
            $content=$header.'//START_HERE//'.$markdown;
            file_put_contents($path.$_P['name'].'.md', $content);
        
            http_response_code(200);
            $data=['msg'=>'DONE'];
          }
        }
    } else {
        unset($_SESSION["username"]);
    }
    
} else {
    $data=['msg'=>'data incorrect'];
}