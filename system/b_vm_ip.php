<?php
if(!empty($_P['zref']) && !empty($_P['zhost']) && !empty($_P['ip']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          $query = _que('SELECT * FROM hosts WHERE host = ?', [$_P['zhost']]);
                if(!is_array($query) || @!isset($query['failed'])){
                    $cloud = $query->fetch(PDO::FETCH_ASSOC);
                    if(!empty($cloud)){
                        $query = _que('SELECT * FROM ip_address WHERE ipv4 = ?', [$_P['ip']]);
                        if(!is_array($query) || @!isset($query['failed'])){
                            $ip = $query->fetch(PDO::FETCH_ASSOC);
                            if(!empty($ip)){
                                require_once'../API/Xen.php';
                                $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
                                $key = PsXenAPI::apref($_P['zref']);
                    
                                $VM=$xen->rq('VM.get_record', [$key]);
                                $VMV = $VM['Value'];
                                if($VMV["power_state"] == 'Running'){
                                    $xen->rq('VM.hard_shutdown', [$key]);
                                }
                                $set_ip=$xen->rq('VIF.configure_ipv4', [$VMV['VIFs'][0],'Static',$ip['ipv4'].'/'.$ip['subnet'],$ip['gateway']]);
                                $xen->rq('VM.start', [$key, False, True]);
                                if($set_ip['Status']!='Success'){
                                    $data = ['msg' => 'ไม่สำเร็จ TwT '.$set_ip['ErrorDescription'][0]];
                                } else {
                                    $name= $ip['ipv4'].' - NULL - ';
                                    @$xen->rq('VM.set_name_label',[$key,$name]);
                                    _que("UPDATE ip_address SET uuid='',available=0 WHERE uuid=?;UPDATE ip_address SET uuid=?,available=1 WHERE ipv4=?", [$_P['zref'],$_P['zref'],$_P['ip']]);
                                    http_response_code(200);
                                    $data = ['msg' => 'สำเร็จ','eval'=>'location.reload()'];
                                }
                            } else {
                                $data=['msg'=>'No IP'];

                            }
                        } else {
                            $data=['msg'=>$query['msg']];
                        }
                        
                    } else {
                        $data=['msg'=>'No host'];

                    }
                } else {
                    $data=['msg'=>$query['msg']];
                }
        }
    } else {
        unset($_SESSION["username"]);
    }
}