<?php
if(!empty($_P['lenght']) && is_numeric($_P['lenght']) && in_array($_P['lenght'], [1,7,30]) &&
!empty($_P['plan']) && !empty($_P['os'])&& !empty($_P['distro'])
&& !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($user)){
                unset($_SESSION["username"]);
            } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
            $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
            $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
            $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
            
            if(!$is_suspended && $is_activated){
                $ips=[];
                $query = _que('SELECT * FROM ip_address WHERE available=0');
                if(!is_array($query) || @!isset($query['failed'])){
                    $ips = $query->fetchAll(PDO::FETCH_ASSOC);
                }
                if(!empty($ips)){
                    $plan_cpu=0;
                    $plan_ram=0;
                    $plan_disk=0;
                    $base_price=0;
                    if($_P['plan']!='Custom'){
                        $query = _que('SELECT * FROM plans WHERE plan=? AND public=1',[$_P['plan']]);
                        if(!is_array($query) || @!isset($query['failed'])){
                            $plan = $query->fetch(PDO::FETCH_ASSOC);
                            $plan_cpu=$plan['cpu'];
                            $plan_ram=$plan['ram'];
                            $plan_disk=$plan['disk'];
                            $base_price=$plan['price'];
                        }
                    } else {
                        $plan_cpu=(@is_numeric($_P['cpu']))?$_P['cpu']:0;
                        $plan_ram=(@is_numeric($_P['ram']))?$_P['ram']:0;
                        $plan_disk=(@is_numeric($_P['disk']) && $_P['disk']>=$disk_minimumn)?$_P['disk']:0;
                        $base_price=($plan_cpu * 6.7)+($plan_ram * 3.4)+($plan_disk * 0.07);
                    }

                    if($plan_cpu>0 && $plan_ram>0 && $plan_disk>=$disk_minimumn){
                        $cal_price=$base_price*$_P['lenght'];
                        if($user['point']>=$cal_price){
                            $query = _que('SELECT * FROM hosts WHERE public=1 AND manitance=0');
                            if(!is_array($query) || @!isset($query['failed'])){
                            $hosts = $query->fetchAll(PDO::FETCH_ASSOC);
                            if(!empty($hosts)){
                                $os=[];
                                $query = _que('SELECT host,os.* FROM os WHERE os = ? AND distro = ?', [$_P['os'],$_P['distro']]);
                                if(!is_array($query) || @!isset($query['failed'])){
                                    $os = $query->fetchAll(PDO::FETCH_GROUP);
                                }
                                    
                                $cvxv = "";
                                if(empty($os)){
                                    $data = ['msg' => 'ไม่มี OS พร้อมติดตั้งในขณะนี้'];
                                } else {
                                    require_once('../API/Xen.php');
                                    $Stat['ready']=false;
                                    $Stat['host']='';
                                    $Stat['user']='';
                                    $Stat['pass']='';
                                    $Stat['template_ref']='';
                                    foreach ($hosts as $host) {
                                        $cvxv .= "0";
                                        if(empty($os[$host['host']])){$cvxv.="1";continue; }
                                        $xen = new PsXenAPI($host['host'],$host['username'],$host['password']);
                                        if(is_null($xen->id_session)){ $cvxv.="4";continue; }
                                        $hosts_data = $xen->rq('host.get_all_records');
                                        if(empty($hosts_data) || $hosts_data['Status'] != 'Success') { $cvxv.="3";continue; }
                                        $mem_free=0;
                                        $cpu_core=0;
                                        foreach ($hosts_data['Value'] as $host_key => $host_data) {
                                            $cpu_corex = intval($host_data['cpu_info']['cpu_count']);
                                            $cpu_core = ($cpu_corex > $cpu_core)?$cpu_corex:$cpu_core;
                                        }
                                        if($cpu_core<$plan_cpu){ $cvxv.="5";continue; }
                                        
                                        $metrics=$xen->rq('host_metrics.get_all_records');
                                        if($metrics['Status']!='Success'){ $cvxv.="6";continue; }
                                        $ram_max=0;
                                        foreach ($metrics['Value'] as $metric) {
                                            $ram_max=$metric['memory_total'];
                                        }
                                        $metrics=$xen->rq('VM_metrics.get_all_records');
                                        if($metrics['Status']!='Success'){ $cvxv.="7";continue; }
                                        $ram_used=0;
                                        foreach ($metrics['Value'] as $metric) {
                                            $ram_used+=$metric['memory_actual'];
                                        }
                                        $mem_free= ($ram_max-$ram_used)-(10*1073741824);
                                        if($mem_free<($plan_ram*1073741824)){ $cvxv.="8";continue; }
                                        $SR=$xen->rq('SR.get_all_records_where', ['field "type" = "lvm" or field "type" = "ext"']);
                                        if($SR['Status']!='Success'){ $cvxv.="9";continue; }
                                        $t_d = 0;
                                        foreach ($SR['Value'] as $ooer) {
                                            $disk_freex = $ooer['physical_size'] -$ooer['physical_utilisation'];
                                            $disk_free=($disk_freex>$diskfree)?$disk_freex:$diskfree;
                                        }
                                        $disk_free=$disk_free-(80*1073741824);
                                        if($disk_free<($plan_disk)){
                                            $cvxv.="X";continue;
                                        }
                                        $Stat['ready']=true;
                                        $Stat['host']=$host['host'];
                                        $Stat['user']=$host['username'];
                                        $Stat['pass']=$host['password'];
                                        $Stat['template_ref']=$os[$host['host']][0]['ref'];
                                        break;
                                    }
                                }
                                if($Stat['ready']){
                                    $og_name=(!empty($_P['name']) && ctype_alnum(str_replace(['-', '_','!','@','.'], '', $_P['name'])) && strlen($_P['name']) <= 20)?$_P['name'] :'VM-'._ranstr(6);
                                    $ipv4 = $ips[array_rand($ips)];
                                    $date = date('Y-m-d H:i:s', time() + ($_P['lenght'] * 86400));
                                    $name= $ipv4['ipv4'].' - '.$user['email'].' - '.$date;
                                    $xen = new PsXenAPI($Stat['host'],$Stat['user'],$Stat['pass']);
                
                                    $clone=$xen->rq('VM.clone', [PsXenAPI::apref($Stat['template_ref']), $name]);
                                    $uuid=$clone['Value'];
                                    if(empty($uuid)){
                                        $xfffff = ($is_admin)?$Stat['host'].' '.json_encode($clone['ErrorDescription']):$clone['ErrorDescription'][0];
                                        $data=['msg' => 'สร้างเครื่องไม่ได้ '. $xfffff];
                                    } else {
                                        $plan_ramx=$plan_ram*1073741824;
                                        $plan_diskx=$plan_disk*1073741824;
                                        $uuid_matted=PsXenAPI::cutref($uuid);
                                        $query = _que('UPDATE customer SET point=point-? WHERE id = ?',[$cal_price,$_SESSION["username"]]);
                                        if(!is_array($query) || @!isset($query['failed'])){
                                            // Gen Password
                                            $characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ';
                                            $charactersLength = strlen($characters);
                                            $randomString = '';
                                            for ($i = 0; $i < 8; $i++) {
                                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                                            }
                                            $password = $randomString.rand(0, 9).'Ab@#';


                                            $order_ref = hash('sha256',(microtime().rand()).$_SESSION["username"].$og_name);
                                            $info = 'Cloud (VPS) '.$_P['plan']." ( {$plan_cpu}C RAM {$plan_ram} GB DISK {$plan_disk} GB ) {$_P['lenght']} Day {$_P['os']} {$_P['distro']}";
                                            $query = _que('INSERT into vm (ref,user_label,cusid,timestamp,plan,lenght,cpu,ram,disk,host,base_price,template,os,distro,user_password) VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
                                            UPDATE ip_address SET uuid=?,available=1 WHERE ipv4=?;
                                            INSERT into order_history (ref,cusid,price,info) values (?,?,?,?);',
                                            [$uuid_matted,$og_name,$_SESSION["username"],$date,$_P['plan'],$_P['lenght'],$plan_cpu,$plan_ram,$plan_disk,$Stat['host'],$base_price,$Stat['template_ref'],$_P['os'],$_P['distro'],$password,
                                            $uuid_matted,$ipv4['ipv4'],
                                            $order_ref,$_SESSION["username"],$cal_price,$info]);
                                            if(!is_array($query) || @!isset($query['failed'])){
                                                $set_mem = $xen->rq('VM.set_memory', [$uuid,strval($plan_ramx)]);
                            
                                                $VM=$xen->rq('VM.get_record', [$uuid]);
                                                $VMV = $VM['Value'];
                                                foreach ($VMV['VBDs'] as $VBDK) {
                                                    $VBD = $xen->rq('VBD.get_record', [$VBDK])['Value'];
                                                    if($VBD['type'] == 'Disk'){
                                                        $VDI= $xen->rq('VDI.resize', [$VBD['VDI'],strval($plan_diskx)]);
                                                    }
                                                }
                                                
                                                $provision=$xen->rq('VM.provision', [$uuid]);
                                                $lf = $xen->rq("VM.remove_from_platform", [$uuid, "cores-per-socket"]);
                                                $cpc = ($plan_cpu%2!=0)?$plan_cpu:($plan_cpu/2);
                                                $lg = $xen->rq("VM.add_to_platform", [$uuid, "cores-per-socket", strval($cpc)]);
                                                //https://discussions.citrix.com/topic/329295-set-memory-and-vcpu/
												$set_core_start = $xen->rq('VM.set_VCPUs_at_startup', [$uuid,$plan_cpu]);
                                                $set_core_max = $xen->rq('VM.set_VCPUs_max', [$uuid,$plan_cpu]);
                            
                                                $set_ip = $xen->rq('VIF.configure_ipv4', [$VMV['VIFs'][0],'Static',$ipv4['ipv4'].'/'.$ipv4['subnet'],$ipv4['gateway']]);
                                                $xenstore = [
                                                    "vm-data/xpwd" => $password,
                                                    "vm-data/ipv4" => $ipv4['ipv4'],
                                                    "vm-data/subnet" => $ipv4['subnet'],
                                                    "vm-data/gateway" => $ipv4['gateway'],
                                                    "vm-data/submark" => $ipv4['subnet']
                                                ];
                                                $set_xenstore_data = $xen->rq('VM.set_xenstore_data', [$uuid, $xenstore]);
                                                $start = $xen->rq('VM.start', [$uuid, False, True]);
                            
                                                http_response_code(200);
                                                $data=['msg' => 'เสร็จ','url'=>'?page=home'];
                                            } else {
                                                $data = ['msg' => $query['msg']];
                                            }
                                    } else {
                                        $data = ['msg' => $query['msg']];
                                    }    
										}
                                } else {
                                    $data = ['msg' => 'ไม่มีเครื่องว่างในขณะนี้ ('.$cvxv.')'];
                                }
                            } else {
                                $data = ['msg' => 'ไม่มีเครื่องว่างในขณะนี้ (2)'];
                            }
                        } else {
                            $data = ['msg' => $query['msg']];
                        }
                    } else {
                        $data = ['msg' => "Your account balance is insufficient."];
                    }
                } else {
                    $data = ['msg' => 'ไม่มีเครื่องว่างในขณะนี้ (1)'];
                }
            } else {
                $data = ['msg' => 'ไม่มี IP ว่างขณะนี้'];
            }
        } else {
            $data = ['msg' => "This account doesn't met requirement."];
        }
    }
} else {
    $data = ['msg' => $query['msg']];
}
} else {
    $data = ['msg' => 'Parameter missing'];
}