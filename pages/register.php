<?php
    if(!empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
?>
<center class="container pb-5">
    <div class="card mt-4" style="max-width:28rem">
        <div class="card-body">
        <form class="text-left" onSubmit='ajax("register",$(this).serialize(),"hcaptcha.reset()");return false;'>
        <input type="number" name="fbid" value="<?= $_GET['fbid']?>" hidden>
        <?php if(!empty($_GET['fbid'])){
            echo'<div class="alert alert-primary text-center">กรุณาสมัครสมาชิก</div>';
            }?>
            <img class="mt-3 mb-5 rounded mx-auto d-block" src="assets/logo_drite.svg" alt="Craft.in.th" width="200">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= L::fname?></label>
                        <input type="text" class="form-control" placeholder="<?= L::fname?>" name="first_name" value="<?= $_GET['fn']?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= L::lname?></label>
                        <input type="text" class="form-control" placeholder="<?= L::lname?>" name="last_name" value="<?= $_GET['ln']?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                    <p class="text-center"><?= L::useengname?></p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><?= L::email?></label>
                <input type="email" class="form-control" placeholder="<?= L::email?>" name="email">
            </div>
            <div class="form-group">
                <label><?= L::password?></label>
                <input type="password" class="form-control" placeholder="<?= L::password?>" name="password">
                <small>ต้องเป็น 8 หลักขึ้นไป</small>
            </div>
            <div class="form-group">
                <label><?= L::confpassword?></label>
                <input type="password" class="form-control" placeholder="<?= L::confpassword?>" name="confirm_password">
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="term" name="accept" value="yes">
                    <label class="custom-control-label" for="term"><?= L::Iagree?> <a href="?page=term" target="_blank"><?= L::Term?></a></label>
                </div>
            </div>
            <center>
            <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>"></div>
            </center>
            <button type="submit" class="btn btn-primary btn-block"><?= L::register?></button>
        </form>
            <p class="my-3"><?= L::hasacc?> <a href="?page=home"><?= L::login?></a></p>
        </div>
    </div>
</center>