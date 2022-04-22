<?php
if(!empty($_G['lenght']) && is_numeric($_G['lenght']) && in_array($_G['lenght'], [1,7,30]) && !empty($_SESSION["username"])){
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
        
        if($is_suspended || !$is_activated){
            die();
        }
        $ips=[];
        $query = _que('SELECT * FROM ip_address WHERE available=0');
        if(!is_array($query) || @!isset($query['failed'])){
            $ips = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        if(empty($ips)){
            http_response_code(200);
            die('<div class="alert alert-danger">ไม่มี IP ว่างในขณะนี้</div>');
        }
        $query = _que('SELECT * FROM hosts WHERE public=1 AND manitance=0');
        if(!is_array($query) || @!isset($query['failed'])){
            $hosts = $query->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($hosts)){
                $os=[];
                $oss=[];
                $query = _que('SELECT host,os.* FROM os');
                if(!is_array($query) || @!isset($query['failed'])){
                    $os = $query->fetchAll(PDO::FETCH_GROUP);
                }
                    
                require_once('../API/Xen.php');
                $Stat['ready']=false;
                $Stat['ram_free']=0;
                $Stat['cpu_core']=0;
                $Stat['disk_free']=0;
                $d_code="";
                foreach($os as $kk => $vv){
                    foreach($vv as $ooss){
                        $oss[$ooss['os']][$ooss['distro']]=true;
                    }
                }
                foreach ($hosts as $host) {
                    if($is_admin){
                        $d_code.="-{$host['host']}-";
                    } else {
                        $d_code.="0";
                    }
                    if(empty($os[$host['host']])){
                        $d_code.="1";
                        continue;
                    }
                    // $oss[$os[$host['host']]['os']][$os[$host['host']]['distro']]=true;
                    $xen = new PsXenAPI($host['host'],$host['username'],$host['password']);
                    if(is_null($xen->id_session)){ 
                        $d_code.="2";continue; }
                    $hosts_data = $xen->rq('host.get_all_records');
                    if(empty($hosts_data) || $hosts_data['Status'] != 'Success') { $d_code.="3";continue; }
                    $cpu_core = ($cpu_corex > $cpu_core)?$cpu_corex:$cpu_core;
                    $mem_free=0;
                    $cpu_core=0;
                    foreach ($hosts_data['Value'] as $host_key => $host_data) {
                        $cpu_corex = intval($host_data['cpu_info']['cpu_count']);
                        $cpu_core = ($cpu_corex > $cpu_core)?$cpu_corex:$cpu_core;
                    }
                    if($cpu_core<1){ 
                        $d_code.="4";continue; }
                    
                    $metrics=$xen->rq('host_metrics.get_all_records');
                    if($metrics['Status']!='Success'){ 
                        $d_code.="5";continue; }
                    $ram_max=0;
                    foreach ($metrics['Value'] as $metric) {
                        $ram_max=$metric['memory_total'];
                    }
                    $metrics=$xen->rq('VM_metrics.get_all_records');
                    if($metrics['Status']!='Success'){ 
                        $d_code.="6";continue; }
                    $ram_used=0;
                    foreach ($metrics['Value'] as $metric) {
                        $ram_used+=$metric['memory_actual'];
                    }
                    if($is_admin){
                        $d_code.="_".floor($ram_max/1073741824)."_".floor($ram_used/1073741824)."_";
                    }
                    $mem_free= (($ram_max-$ram_used)-(1073741824*10));
                    if($mem_free<1073741824){ 
                        $d_code.="7";continue; }
                    $SR=$xen->rq('SR.get_all_records_where', ['field "type" = "lvm" or field "type" = "ext"']);
                    if($SR['Status']!='Success'){ 
                        $d_code.="8";continue; }
                    $disk_free=0;
                    
                    $t_d = 0;
                    foreach ($SR['Value'] as $ooer) {
                        $disk_freex = $ooer['physical_size'] -$ooer['physical_utilisation'];
                        $disk_free=($disk_freex>$diskfree)?$disk_freex:$diskfree;
                    }
                    $disk_free=$disk_free-(80*1073741824);
                    if($disk_free<($disk_minimumn*1073741824)){
                        $d_code.="9";continue;
                    }
                    
                    if($mem_free>$Stat['ram_free']){
                        $d_code.="Y";
                        $Stat['ram_free']=$mem_free;
                        $Stat['cpu_core']=$cpu_core;
                        $Stat['disk_free']=$disk_free;
                    }
                    $Stat['ready']=true;
                }
                $plans=[];
                $ram_max=floor($Stat['ram_free']/1073741824);
                $disk_max=floor(($Stat['disk_free']/1073741824)/10)*10;
                $query = _que('SELECT * FROM plans WHERE cpu <= ? AND ram <= ? AND disk <= ? AND public=1',[$Stat['cpu_core'],$ram_max,$disk_max]);
                if(!is_array($query) || @!isset($query['failed'])){
                    $plans = $query->fetchAll(PDO::FETCH_ASSOC);
                }
               
                if(empty($plans)){
                    $d_code.="X";
                    $Stat['ready']=false;
                }
                if($Stat['ready']){
                    http_response_code(200);
                    ?>
                    <hr>
                    <h5><?= L::image?></h5>
                    <div class="row" id="os_selector">
                        <?php
                            foreach($oss as $k=>$v){
								$tmp=array_keys($v);
									sort($tmp);
                                ?>
                                    <div class="col-lg-3">
                                        <div class="dropdown box mb-2">
                                            <button class="btn btn-link p-0 dropdown-toggle btn-sm btn btn-block text-reset text-decoration-none" data-toggle="dropdown">
                                                <div class="px-1 py-2 text-center border-bottom">
                                                    <i class="fab fa-<?= strtolower($k)?> h1 mb-0"></i>
                                                    <p class="mb-0" style="font-size:12px;"><?= $k?></p>
                                                </div>
                                                <small><?= L::selectversion?></small>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-center">
                                                <?php
                                                    foreach ($tmp as $distro) {
                                                        ?>
                                                            <button class="dropdown-item" onclick="os($(this))"><?= $distro?></button>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>
                        
                    </div>
                    <hr>
                    <h5><?= L::plan?></h5>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#plan-package"><?= L::package?></a>
                            <a class="nav-item nav-link" data-toggle="tab" href="#plan-custom"><?= L::custom?></a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="plan-package">
                        <div class="row pt-4 px-0" id="plan_selector">
                            <?php
                            foreach ($plans as $k => $v) {
                                ?>
                                <div class="col-lg-3">
                                    <button class="btn btn-link p-0 btn-block mb-2 text-reset text-decoration-none" onclick="plan($(this))"
                                    data-bp="<?=$v['price']?>" 
                                    data-plan="<?=$v['plan']?>" 
                                    data-cpu="<?=$v['cpu']?>" 
                                    data-ram="<?=$v['ram']?>" 
                                    data-disk="<?=$v['disk']?>" >
                                        <div class="box pt-2">
                                            <p class="mb-1 d-flex align-items-center justify-content-center" style="font-size:12px;"><i class="fal fa-server h4 mb-0 mr-2"></i> Package <?= ($k+1)?></p>
                                            <p style="font-size:12px;line-height:14px;" class="mb-0">
                                                <i class="fal fa-microchip"></i> <?=$v['cpu']?> C</br>
                                                <i class="fal fa-memory"></i> <?=$v['ram']?> GB</br>
                                                <i class="fal fa-hdd"></i> <?=$v['disk']?> GB
                                            </p>
                                        <div class="px-1 py-2 text-center">
                                            <p class="mb-0"><?=$v['price']?>฿ / <?= L::day?></p>
                                            <p class="mb-0"><?=$s =$v['price']*7?>฿ / <?= L::sat?></p>
                                            <p class="mb-0"><?=$m =$v['price']*30?>฿ / <?= L::month?></p>
                                        </div>
                                        </div>
                                    </button>
                                </div>
                                <?php
                            }
                            ?>
                            
                        </div>
                        </div>
                        <div class="tab-pane fade=" id="plan-custom">
                            <div class="pt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <label class="mb-0"><?= L::cpu?> <?= L::core?> ( <?= L::lowest?> 1 <?= L::core?> <?= L::highest?> <?= $Stat['cpu_core']?> <?= L::core?>)</label>
                                    <span class="badge badge-primary d-flex align-items-center" id="pr-cpu"></span>
                                </div>
                                <input type="range" class="custom-range" id="cpu_custom" min="1" max="<?= $Stat['cpu_core']?>" step="1" value="1" onInput="cpu($(this).val())">
                            </div>
                            <div class="pt-4">
                            <div class="d-flex justify-content-between mb-2">
                                    <label class="mb-0"><?= L::ram?> ( <?= L::lowest?> 1 GB <?= L::highest?> <?= $ram_max?> GB)</label>
                                    <span class="badge badge-primary d-flex align-items-center" id="pr-ram"></span>
                                </div>
                                <input type="range" class="custom-range" min="1" max="<?= $ram_max?>" step="1" value="1" onInput="ram($(this).val())">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <label class="mb-0"><?= L::disk?> ( <?= L::lowest?> <?=$disk_minimumn?> GB <?= L::highest?> <?= $disk_max?> GB )</label>
                                    <span class="badge badge-primary d-flex align-items-center" id="pr-hdd"></span>
                                </div>
                                <input type="range" class="custom-range" min="<?=$disk_minimumn?>" max="<?= $disk_max?>" step="10" value="1" onInput="hdd($(this).val())">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5><?= L::option?></h5>
                    <div class="form-group">
                        <label><?= L::vmnameoption?></label>
                        <input type="text" class="form-control" onInput="$('form#checkout input[name$=\'name\']').val($(this).val())">
                        <small><?= L::vmnameonly?></small>
                    </div>
                    <script>
                        $('#checkout-spec').show();
                        cpu()
                        ram()
                        hdd()
                        $('form#checkout input[name$="os"]').val('');
                        $('form#checkout input[name$="distro"]').val('');
                        $('form#checkout input[name$="name"]').val('');
                        $('#plan-os').text('');
                        $('#plan-distro').text('');
                    </script>
                    <?php
                } else {
                    http_response_code(200);
                    die('<div class="alert alert-danger">ไม่มีเครื่องว่างในขณะนี้<hr>'.$d_code.'</div>');
                }
            }
        } else {
            
            http_response_code(200);
            die("<div class='alert alert-danger'>{$query['msg']}</div>");
        }
        }
    } else {
        unset($_SESSION["username"]);
    }
}
die(); 