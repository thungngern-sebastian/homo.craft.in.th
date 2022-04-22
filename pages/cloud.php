<?php
    if(empty($_SESSION['username']) || empty($_GET['ref'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
$secret="aCf#+Dtjm$+4yx=f-HbqvMYScM+BG_xSFuF=7rkkwtF3@r&hc@qgCPJQyRNT+aU+";
    $cloud=[];
    $query = _que('SELECT * FROM vm 
    INNER JOIN ip_address ON vm.ref = ip_address.uuid 
    INNER JOIN hosts ON vm.host = hosts.host
    WHERE cusid=? AND ref=?',[$_SESSION['username'],$_GET['ref']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $cloud = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(empty($cloud)){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $GMET="";
    require_once'../API/Xen.php';
    if($cloud['manitance']==1){
      $vm_['status']='Manitance';
    } else if($cloud['pause']==1){
      $vm_['status']='Expired';
    } else {
      $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
      $vm_['status']='Offline';
      $vm_data = $xen->rq('VM.get_record',[PsXenAPI::apref($_GET['ref'])]);
      if($vm_data['Status']=='Success'){
        $vm_data=$vm_data['Value'];
          $vm_['status']=($vm_data["power_state"] == 'Running')?'Online':'Offline';
      }
    }
?>
<div class="container pb-5">
<div class="row">
  <div class="col-lg-4 mb-3">
    <div class="list-group">
      <a class="list-group-item list-group-item-action pt-3 pb-3 active" data-toggle="list" href="#p-overview"><i class="fas fa-sliders-v-square mr-2"></i><?= L::overview?></a>
      <a class="list-group-item list-group-item-action pt-3 pb-3" data-toggle="list" href="#p-performance"><i class="fas fa-tachometer-alt mr-2"></i><?= L::performance?></a>
      <a class="list-group-item list-group-item-action pt-3 pb-3" data-toggle="list" href="#p-setting"><i class="fas fa-wrench mr-2"></i><?= L::setting?></a>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
          <div class="d-flex flex-row justify-content-between align-items-center mb-3">
            <div class="d-flex flex-row align-items-center">
              <div class="border super-xx rounded-circle d-flex justify-content-center align-items-center mr-2" style="width:34px;height:34px;"><i class="fab fa-<?= strtolower($cloud['os'])?> h5 mb-0"></i></div>
                <div>
                  <h5 class="mb-n1"><?= $cloud['user_label']?></h5>
                  <p class="mb-0"><?= $cloud['ipv4']?></p>
                </div>
            </div>
            <h4 class="mb-0"><span class="badge badge-primary font-weight-normal"><?=$vm_['status']?></span></h4>
          </div>
          <hr class="mt-1">
              <!-- <?php if($vm_['status']=='Manitance' || $vm_['status']=='Expired' || $vm_['status']=='Offline') {
              ?>
              <div class="alert alert-warning" role="alert">
                Some feature will not work due to Manitance / Expired / Offline
              </div>
              <?php
              }
              ?> -->
          <div class="tab-content">
            <div class="tab-pane fade show active" id="p-overview">
              <div class="row">
                <div class="col-md-12">
				<div class="card card-body text-center pt-5 pb-5 mt-2 mb-2">
					<h1><b>Home</b></h1>
                  <h5>System Overview</h5>
				</div>
				<div class="row">
				<div class="col-md-6">
				<div class="card card-body text-center mt-2 mb-2">
					<div class="d-flex justify-content-between">
						  <div>
							 <b><i class="fas fa-server"></i></b>
						  </div>
						  <div>
							 Server Details
						  </div>
					 </div>
					<hr>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::plan?></b>
						  </div>
						  <div>
							 <?= $cloud['plan']?>
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::cpu?></b>
						  </div>
						  <div>
							 <?= $cloud['cpu']?> <?= L::core?>
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::ram?></b>
						  </div>
						  <div>
							 <?= $cloud['ram']?> GB
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::diskall?></b>
						  </div>
						  <div>
							 <?= $cloud['disk']?> GB
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::price?></b>
						  </div>
						  <div>
							 <?= $cloud['base_price'] * $cloud['lenght']?>฿ (<?=$cloud['lenght']?> <?= L::day?>)
						  </div>
					 </div>
					</div>
					</div>
					<div class="col-md-6">
					<div class="card card-body text-center mt-2 mb-2">
					<div class="d-flex justify-content-between">
						  <div>
							 <b><i class="fas fa-info-circle"></i></b>
						  </div>
						  <div>
							 Server Information
						  </div>
					 </div>
					<hr>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::os?></b>
						  </div>
						  <div>
							 <?= $cloud['os']?>
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::distro?></b>
						  </div>
						  <div>
							 <?= $cloud['distro']?>
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b><?= L::exprirationdate?></b>
						  </div>
						  <div>
							 <?= $cloud['timestamp']?>
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b>Username</b>
						  </div>
						  <div>
							  <?= ($cloud['os']=="Linux")?"root":"Administrator"?>
						  </div>
					 </div>
					<div class="d-flex justify-content-between">
						  <div>
							 <b>Password</b>
						  </div>
						  <div>
							 <?= $cloud['user_password']?>
						  </div>
					 </div>
					</div>
					</div>
					</div>
                </div>
				<div class="col-md-12 mt-4">
                  <?php
                    if($vm_['status']=='Online'){
                      ?>
					  <div class="row">
						  <div class="col-md-6 mb-2">
                        <button class="btn btn-danger btn-block" onclick="ajax('control_cloud',{'ref':'<?= $_GET['ref']?>','action':'shutdown'})"><i class="fas fa-power-off mr-2"></i><?= L::shutdown?></button>
						  </div>
						  <div class="col-md-6">
                        <button class="btn btn-danger btn-block" onclick="ajax('control_cloud',{'ref':'<?= $_GET['ref']?>','action':'restart'})"><i class="fas fa-undo mr-2"></i><?= L::restart?></button>
						  </div>
					  </div>
					<a href="https://console.controlpanel.craft.in.th/?ref=<?= $_GET['ref']?>&code=<?= hash("sha256",$_GET['ref']."|".$secret)?>" class="btn btn-primary btn-block mt-2" target="_blank"><i class="fas fa-desktop mr-2"></i>Console</a>
                      <?php
                    } elseif($vm_['status']=='Offline'){
                      ?>
                        <button class="btn btn-success btn-block" onclick="ajax('control_cloud',{'ref':'<?= $_GET['ref']?>','action':'start'})"><i class="fas fa-play mr-2"></i>Start</button>

                      <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <div class="tab-pane fade show" id="p-performance">
              <p class="mb-1"><?= L::cpu?> <span id="cpu_stat"></span></p>
                  <canvas id="cpu_chaa" width="400" height="70"></canvas>

              <p class="mb-1"><?= L::ram?> <span id="ram_stat"></span></p>
                  <canvas id="ram_chaa" width="400" height="70"></canvas>

              <p class="mb-1"><?= L::networkdownload?> <span id="down_stat"></span></p>
                  <canvas id="download_chaa" width="400" height="70"></canvas>

              <p class="mb-1"><?= L::networkupload?> <span id="up_stat"></span></p>
                  <canvas id="upload_chaa" width="400" height="70"></canvas>
                  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                  <script>
                    var optionn = {
                          scales: {
                              yAxes: [{
                                  stacked: true,
                                  ticks: {
                                      display: false,
                                      suggestedMax: 100,
                                      beginAtZero: true
                                  }
                              }],
                              xAxes: [{
                                  ticks: {
                                      display: false
                                  }
                              }]
                          },
                          legend: { display: false },
                          bezierCurve: false,
                          spanGaps: false,
                          elements: {
                            line: {
                              tension: 0.000001,
                              borderWidth: 1
                            },     
                              point:{
                                  radius: 0
                              }
                          }
                        };
                    var ctx = document.getElementById('cpu_chaa').getContext('2d');
                    var cpu_chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                backgroundColor: 'rgba(17, 125, 187,.1)',
                                borderColor: 'rgb(17, 125, 187)',
                                data: []
                            }]
                        },
                        options: optionn
                    });
                    ctx = document.getElementById('ram_chaa').getContext('2d');
                    var ram_chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                backgroundColor: 'rgba(139, 18, 174,.1)',
                                borderColor: 'rgb(139, 18, 174)',
                                data: []
                            }]
                        },
                        options: optionn
                    });
                    ctx = document.getElementById('download_chaa').getContext('2d');
                    var download_chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                backgroundColor: 'rgba(167, 79,1,.1)',
                                borderColor: 'rgb(167, 79,1)',
                                data: []
                            }]
                        },
                        options: optionn
                    });
                    ctx = document.getElementById('upload_chaa').getContext('2d');
                    var upload_chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                backgroundColor: 'rgba(167, 79,1,.1)',
                                borderColor: 'rgb(167, 79,1)',
                                data: []
                            }]
                        },
                        options: optionn
                    });
                  </script>
            </div>
            <div class="tab-pane fade show" id="p-setting">
                <form onSubmit="ajax('chg_vm_name', $(this).serialize());return false;" class="mb-3">
                    <label><?= L::newmachinename?></label>
                    <input name="ref" value="<?= $_GET['ref']?>" hidden>
                    <div class="input-group">
                      <input type="text" class="form-control" name="name">
                      <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="submit">เปลี่ยนชื่อ</button>
                      </div>
                    </div>
                    <small><?= L::vmnameonly?></small>
                </form>
                
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between mt-3 mb-3">
                  <div>
                    <p class="mb-0"><?= L::resetip?></p>
                    <small><?= L::cannotaccess?></small>
                  </div>
                  <div>
                    <button class="btn btn-danger px-4" <?= ($vm_['status']!='Manitance'&&$vm_['status']!='Expired')?'onclick="ajax(\'reset_ip\',{\'ref\':\''.$_GET['ref'].'\'})"':'disabled'?>><?= L::resetip?></button>
                  </div>
                </div>
                
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between mt-3 mb-3">
                  <div>
                    <p class="mb-0"><?= L::reset?></p>
                    <small><?= L::cannotuse?></small>
                  </div>
                  <div>
                  <button class="btn btn-danger px-4" data-toggle="modal" data-target="#reset_vm_modal" <?= ($vm_['status']=='Manitance'||$vm_['status']=='Expired')?'disabled':''?>><?= L::reset?></button>
                  </div>
                </div>
                
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between mt-3 mb-3">
                  <div>
                    <p class="mb-0"><?= L::deletethiscloud?></p>
                    <small><?= L::deletevm?></small>
                  </div>
                  <div>
                    <button class="btn btn-danger px-4" data-toggle="modal" data-target="#delete_vm_modal" <?= ($vm_['status']=='Manitance')?'disabled':''?>><?= L::delete?></button>
                  </div>
                </div>
               
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between mt-3 mb-3">
                  <div>
                    <p class="mb-0">Renewal</p>
					          <small>If you want to opened/closed renewal function.</small>
                  </div>
                  <div>
                    <button class="btn btn-primary btn-sm px-4" onclick="ajax('autorenew_setting',{'ref':'<?=$_GET['ref']?>',<?= ($cloud['autorenew']==1)?'':'\'c\':true' ?>})"><?= ($cloud['autorenew']==1)?'Opened':'Closed'?></button>
                  </div>
                </div>
				
                  <button class="btn btn-success btn-block" onclick="ajax('renew',{'ref':'<?= $_GET['ref']?>'})"><?= L::renew?></button>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php if($vm_['status']!='Manitance') {
  ?>
<div class="modal fade" id="delete_vm_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title"><?= L::deletethiscloud?></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4><?= L::deletevm?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">ไม่ยืนยัน</button>
        <button type="button" class="btn btn-primary btn-danger" onclick="$('#delete_vm_modal').modal('toggle');ajax('delete_vm',{'ref':'<?= $_GET['ref']?>'})"><?= L::delete?></button>
      </div>
    </div>
  </div>
</div>
  <?php
}
if($vm_['status']!='Expired' && $vm_['status']!='Manitance') {
  ?>
<div class="modal fade" id="reset_vm_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title">Reset cloud</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>คุณยืนยันที่จะ Reset เครื่องเซิร์ฟเวอร์หรือไม่ (ห้ามปิดหน้านี้จนกว่าจะทำรายการเสร็จ อยากรู้ก็ปิดดู)</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">ไม่ยืนยัน</button>
        <button type="button" class="btn btn-primary btn-danger" onclick="$('#reset_vm_modal').modal('toggle');ajax('reset_os',{'ref':'<?= $_GET['ref']?>'})"><?= L::reset?></button>
      </div>
    </div>
  </div>
</div>
<script>
  function fetch_vm_stat() {
    $.getJSON('a.php?a=cloud_stat&ref=<?= $cloud['ref']?>', function(res) {
        if(res['status']==true){
                  
                  var d = new Date();
                  var tt = d.getHours() + ":"+ d.getMinutes();
                  cpu_chart.data.labels.push(tt);
                  cpu_chart.data.datasets.forEach((dataset) => {
                      dataset.data.push(res['cpu']);
                  });
                  cpu_chart.update();

                  ram_chart.data.labels.push(tt);
                  ram_chart.data.datasets.forEach((dataset) => {
                      dataset.data.push(res['ram']['percent']);
                  });
                  ram_chart.update();

                  download_chart.data.labels.push(tt);
                  download_chart.data.datasets.forEach((dataset) => {
                      dataset.data.push(res['net']['down_per']);
                  });
                  download_chart.update();

                  upload_chart.data.labels.push(tt);
                  upload_chart.data.datasets.forEach((dataset) => {
                      dataset.data.push(res['net']['up_per']);
                  });
                  upload_chart.update();
            
            $('#ram_stat').text(res['ram']['used']+'/'+res['ram']['max']+' '+res['ram']['percent']+'%')
            $('#down_stat').text(res['net']['down']+'/s')
            $('#up_stat').text(res['net']['up']+'/s')
            $('#cpu_stat').text(res['cpu']+'%')
        }
    }).always(
        function(){
            setTimeout(function(){ fetch_vm_stat(); }, 5000);
        }
    );
  }
  $(document).ready(function(){
  fetch_vm_stat();
  });
</script>
  <?php
}
?>

</div>