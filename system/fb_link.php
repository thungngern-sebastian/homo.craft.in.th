<?php
if(!empty($_SESSION['username']) && !empty($_P['token'])){

    require_once '../API/facebook/autoload.php';
    $fb = new Facebook\Facebook([
        'app_id' => '1061786614275267',
        'app_secret' => '839d2a560aac3451638598caef428f4c',
        'default_graph_version' => 'v6.0',
        ]);    
    try {
        $response = $fb->get(
            '/me?fields=id,last_name,first_name,picture.width(100).height(100)',
            $_P['token']
        );
    } 
    catch(FacebookExceptionsFacebookResponseException $e) {}
    catch(FacebookExceptionsFacebookSDKException $e) {}
    $graphNode = $response->getGraphNode();
    if(!empty($graphNode)){
        $query = _que('SELECT * FROM customer where fbid = ?', [$graphNode['id']]);
        if(!is_array($query) || @!isset($query['failed'])){
            $pf_img=null;
            $image = file_get_contents($graphNode['picture']['url']);
            if ($image !== false){
                $pf_img = 'data:image/jpg;base64,'.base64_encode($image);
            }
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($user)){
                $query = _que('UPDATE customer SET fbid = ? WHERE id=?',[$graphNode['id'],$_SESSION['username']]);
                if(!empty($pf_img)){
                    $query = _que('UPDATE customer SET pf_img = ? WHERE id=?',[$pf_img,$_SESSION['username']]);
                }
                http_response_code(200);
                $data = ['msg' => L::complete,'eval'=>'window.location.reload()'];
            } else {
                $data = ['msg' =>'Facebook นี้ได้ผูกกับบัญชีอื่นแล้ว'];
            }
        } else {
            $data = ['msg' =>$query['msg']];
        }
    }
}