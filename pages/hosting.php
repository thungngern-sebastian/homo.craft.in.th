<?php
	if(empty($_SESSION['username']) || empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
	    die('<script>window.location.replace("?page=home")</script>');
	}
	$query = _que('SELECT * FROM `hosting` WHERE `cusid` = ? AND `id` = ?', [$_SESSION['username'], $_GET['id']]);
	if(!is_array($query) || @!isset($query['failed'])){
	    $hosting = $query->fetch(PDO::FETCH_ASSOC);
	}
	if(empty($hosting)){
	    die('<script>window.location.replace("?page=home")</script>');
	}
    $hosting['status'] = 'Active';
    if(new DateTime($hosting['expiration_date']) <= new DateTime()) {
        $hosting['status'] = 'Expired';
    }
?>
<div class="container pb-5">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                <div class="d-flex flex-row align-items-center">
                    <div class="border super-xx rounded-circle d-flex justify-content-center align-items-center mr-2" style="width:34px;height:34px;">
                        <i class="fas fa-cloud h5 mb-0"></i>
                    </div>
                    <div>
                        <h5 class="mb-n1"><?= $hosting['def_domain']?></h5>
						<p class="mb-0">43.229.151.97</p>
                    </div>
                </div>
                <h4 class="mb-0"><span class="badge badge-primary font-weight-normal"><?=$hosting['status']?></span></h4>
            </div>
            <hr class="mt-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
						<div class="card card-body text-center pt-5 pb-5 mt-2 mb-2 col-md-12">
							<h1><b>Home</b></h1>
						  <h5>System Overview</h5>
						</div>
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
                                        <b>Domain</b>
                                    </div>
                                    <div>
                                        <?= $hosting['domain']?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b>Email</b>
                                    </div>
                                    <div>
                                        <?= $hosting['email']?> Account
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b>Database</b>
                                    </div>
                                    <div>
                                        <?= $hosting['dbcount']?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b><?= L::disk?></b>
                                    </div>
                                    <div>
                                        <?= $hosting['disk']?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b>Bandwidth</b>
                                    </div>
                                    <div>
                                        <?= $hosting['traffic']?> GB
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b><?= L::price?></b>
                                    </div>
                                    <div>
                                        <?= $hosting['base_price']?>฿ / <?=$hosting['duration']?> <?= L::day?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
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
                                        <b><?= L::exprirationdate?></b>
                                    </div>
                                    <div>
                                        <?= $hosting['expiration_date']?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b>Username</b>
                                    </div>
                                    <div>
                                        <?= $hosting['username']?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <b>Password</b>
                                    </div>
                                    <div>
                                        <?= $hosting['password']?>
                                    </div>
                                </div>
								<div class="d-flex justify-content-between">
                                    <div>
                                        <b>NameServer 1</b>
                                    </div>
                                    <div>
                                        NS1.DRITE.IN.TH
                                    </div>
                                </div>
								<div class="d-flex justify-content-between">
                                    <div>
                                        <b>NameServer 2</b>
                                    </div>
                                    <div>
                                        NS2.DRITE.IN.TH
                                    </div>
                                </div>
								<div class="d-flex justify-content-between">
                                    <div>
                                        <b>Plesk Panel</b>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary btn-sm btn-block" onclick="ajax('hosting_panel', {'id': <?=$hosting['id']?>})">Control Panel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6 mb-3">
						    <button class="btn btn-primary btn-block" onclick="ajax('hosting_renew', {'id': <?=$hosting['id']?>})">Renewal</button>
						</div>
						<div class="col-md-6">
                            <button class="btn btn-<?= ($hosting['autorenew'] == 1) ? 'danger' : 'success' ?> btn-block mb-3" onclick="ajax('hosting_autorenew_setting', {'id': <?=$hosting['id']?><?= ($hosting['autorenew'] == 1) ? '' : ', \'c\':true' ?>})"><?= ($hosting['autorenew'] == 1) ? 'Disable Renewal' : 'Enable Renewal' ?></button>
						</div>
						<div class="col-md-12">
                            <button class="btn btn-danger btn-block px-4" data-toggle="modal" data-target="#delete_modal">Delete Hosting</button>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title"><?= L::deletethiscloud?></p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>If this Hosting is deleted, data cannot be recovered.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-danger btn-block" onclick="$('#delete_modal').modal('hide');ajax('delete_hosting', {'id': <?= $hosting['id']?>})"><?= L::delete?></button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>