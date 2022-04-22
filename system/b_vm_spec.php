<?php
if(!empty($_P['ref']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if(!$is_admin){die();}
          $query = _que('SELECT *,hosts.username as hostusername FROM vm 
          INNER JOIN hosts ON vm.host = hosts.host 
          WHERE vm.ref = ?', [$_P['ref']]);
                if(!is_array($query) || @!isset($query['failed'])){
                    $cloud = $query->fetch(PDO::FETCH_ASSOC);
                    if(!empty($cloud)){
                        require_once'../API/Xen.php';
                        $xen = new PsXenAPI($cloud['host'],$cloud['hostusername'],$cloud['password']);
                        $key = PsXenAPI::apref($_P['ref']);
            
                        $VM=$xen->rq('VM.get_record', [$key]);
                        $VMV = $VM['Value'];
                        if($VMV["power_state"] == 'Running'){
                            $xen->rq('VM.hard_shutdown', [$key]);
                        }
                        $plan_ramx=$cloud['ram']*1073741824;
                        $plan_disx=$cloud['disk']*1073741824;
                        $set_mem = $xen->rq('VM.set_memory', [$key,strval($plan_ramx)]);

                        foreach ($VMV['VBDs'] as $VBDK) {
                            $VBD = $xen->rq('VBD.get_record', [$VBDK])['Value'];
                            if($VBD['type'] == 'Disk'){
                                $VDI= $xen->rq('VDI.resize', [$VBD['VDI'],strval($plan_disx)]);
                            }
                        }

                        $lf = $xen->rq("VM.remove_from_platform", [$key, "cores-per-socket"]);
                        $cpc = (!is_long($cloud['cpu']/2))?$cloud['cpu']:($cloud['cpu']/2);
                        $lg = $xen->rq("VM.add_to_platform", [$key, "cores-per-socket", strval($cpc)]);
    
                        //https://discussions.citrix.com/topic/329295-set-memory-and-vcpu/
                        $set_core_max = $xen->rq('VM.set_VCPUs_max', [$key,$cloud['cpu']]);
                        $set_core_start = $xen->rq('VM.set_VCPUs_at_startup', [$key,$cloud['cpu']]);

                        $set_ip = $xen->rq('VM.start', [$key, False, True]);
                        if($set_ip['Status']!='Success'){
                            $data = ['msg' => 'ไม่สำเร็จ TwT '.$set_ip['ErrorDescription'][0]];
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
    } else {
        unset($_SESSION["username"]);
    }
}