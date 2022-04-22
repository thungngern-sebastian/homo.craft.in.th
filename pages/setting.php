<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
?>
<div class="container pb-5">
<div class="card">
<div class="card-body">
<div class="col-md-12">
	<div class="text-center mt-5 mb-5">
        <img src="<?= (!empty($user['pf_img'])?$user['pf_img']:$pf_img)?>" style="max-width:150px;" class="rounded-circle mr-0 mr-md-3">
			<h4 class="mt-4 b-0"><b><?= $user['fname']?> <?= $user['lname']?></b></h4>
			<p class="mb-0">Authentication ID #<?= $user['id']?></p>
    </div>
</div>
<div class="row mx-auto">
  <div class="col-md-4">
    <div class="list-group mb-4" id="list-tab" role="tablist">
      <a class="list-group-item list-group-item-action active" data-toggle="list" href="#list-profile"><?= L::profile?></a>
      <a class="list-group-item list-group-item-action" data-toggle="list" href="#list-settings"><?= L::setting?></a>
      <form onSubmit='ajax("delete_user",$(this).serialize(),"hcaptcha.reset()");return false;'>
        <button type="button" class="list-group-item list-group-item-action bg-danger" data-toggle="modal" data-target="#exampleModal"><?= L::Delete_User_Comfirm?></button>
      </form>
    </div>
  </div>
  <div class="col-md-8 mb-5">
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="list-profile">
		  <h4><b>Profile</b></h4>
            <div class="mb-3">
              <p class="mb-0">Link account with facebook</p>
              <small>สามารถเข้าสู่ระบบด้วย Facebook</small>
            </div>
                <?php
                    if(empty($user['fbid'])){
                        ?>
                            <div class="fb-login-button" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="true" data-scope="public_profile" data-onlogin="fb_link();"></div>
                        <?php
                    } else {
                        ?>
                            <button class="btn btn-primary btn-block"onclick="ajax('fb_unlink')">Unlink</button>
                        <?php
                    }
                ?>
          <h4 class="mt-5"><b><?= L::idcard?></b></h4>
          <?php
              if(empty($user['id_card'])){ ?> 
            <form onSubmit='ajax("id_verify",$(this).serialize());return false;'>
                <div class="form-group">
                    <label><?= L::thaiid?></label>
                    <input type="text" class="form-control" name="thid" minlength="13">
                </div>
                <div class="form-group">
                  <label><?= L::provide?></label>
                  <select class="form-control" name="province">
                    <option></option>
                    <?php
                    $_xx=(!empty($_SESSION['lang']) && $_SESSION['lang']=='th')?'th':'en';
                      $province= json_decode(file_get_contents('../data/provinces.json'),true);
                      foreach ($province as $c) {
                        ?>
                          <option value="<?=$c['enName']?>"><?=$c[$_xx.'Name']?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><?= L::verify?></button>
            </form>
          <?php } else {?>
            <p class="mb-0">Verify Success</p>
		  	<small>ยืนยันสำเร็จ</small>
          <?php } ?> 
                <h4 class="mt-5"><b>Two Factor Authentication</b></h4>
          <?php 
              if(empty($user['2fa'])){
                ?>
                
            <form class="text-left" onSubmit='ajax("2fa_enable",$(this).serialize());return false;'>
                <div class="form-group">
                    <label>รหัสผ่านของบัญชีนี้</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Enable 2FA</button>
            </form>
                <?php

              } else if(!empty($_SESSION['sc_check'])) {
                
                require_once '../API/googleauten.php';
      
                $ga = new PHPGangsta_GoogleAuthenticator();
                $secret = $user['2fa'];
                $qrCodeUrl = $ga->getQRCodeGoogleUrl('controlpanel.craft.in.th', $secret);
                ?>
                <img src="<?= $qrCodeUrl?>" class="mt-2 mb-2" style="max-width:200px;">
                <p>Secret Key <b><?= $secret?></b></p>
                <button type="button" class="btn btn-primary btn-block" onclick="ajax('2fa_enable',{})">Reset 2FA</button>
                <?php
              } else {
                ?>
                
                <form class="text-left" onSubmit='ajax("2fa_acc_chk",$(this).serialize());return false;'>
                    <div class="form-group">
                        <label>รหัสผ่านของบัญชีนี้</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">ยืนยันตัวตน</button>
                </form>
                <?php
              }
          ?>
      </div>
      <div class="tab-pane fade" id="list-settings">
          <h4><b><?= L::setting?></b></h4>
          <p><?= L::changepassword?></p>
            <form onSubmit='ajax("chg_pass",$(this).serialize());return false;'>
                <div class="form-group">
                    <label><?= L::oldpassword?></label>
                    <input type="password" class="form-control" name="old_password">
                </div>
                <div class="form-group">
                    <label><?= L::newpassword?></label>
                    <input type="password" class="form-control" name="new_password">
                </div>
                <div class="form-group">
                    <label><?= L::confirmpassword?></label>
                    <input type="password" class="form-control" name="confirm_password">
                </div>
                <button type="submit" class="btn btn-primary btn-block"><?= L::changepassword?></button>
            </form>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?= L::Delete_User_Comfirm?></h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form onSubmit='ajax("delete_user",$(this).serialize(),"hcaptcha.reset()");return false;'>
          <div class="modal-body">
            <center>
              <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>"></div>
            </center>
            <br>
            <button type="submit" class="btn btn-danger btn-block"><?= L::Delete_User_Comfirm?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<script>
function fb_link() {
    FB.getLoginStatus(function(response) {
      ajax('fb_link',{'token':response['authResponse']['accessToken']});
    });
  }
</script>
</div>