<?php

if(!empty($_SESSION['username']) && !empty($_P['ref']) && !empty($_P['host']) && !empty($_P['action'])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
            if($is_admin){
                $query = _que('SELECT * FROM hosts WHERE host = ?', [$_P['host']]);
                if(!is_array($query) || @!isset($query['failed'])){
                    $cloud = $query->fetch(PDO::FETCH_ASSOC);
                    if(!empty($cloud)){
                        require_once'../API/Xen.php';
                        $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
                        $key = PsXenAPI::apref($_P['ref']);
                        switch ($_P['action']) {
                            case 'shutdown':  
                                $data=$xen->rq('VM.shutdown', [$key]);
                                break;
                            case 'restart':
                                $data=$xen->rq('VM.clean_reboot', [$key]);
                                break;
                            case 'forceshutdown':
                                $data=$xen->rq('VM.hard_shutdown', [$key]);
                                break;
                            case 'forcerestart':
                                $data=$xen->rq('VM.hard_reboot', [$key]);
                                break;
                            case 'start':
                                $data=$xen->rq('VM.start', [$key,false,true]);
                                break;
                        }
                        if($data['Status']!='Success'){
                            $data = ['msg' => 'ไม่สำเร็จ '.@$data['ErrorDescription'][0]];
                        } else {
                            http_response_code(200);
                            $data = ['msg' => 'สำเร็จ','eval'=>'location.reload()'];
                        }
                    } else {
                        $data=['msg'=>'No host'];

                    }
                } else {
                    $data=['msg'=>$query['msg']];
                }
            }
        }
    } else {
        unset($_SESSION["username"]);
    }
} else{
    $data=['msg'=>'Data incorrect!'];
}
