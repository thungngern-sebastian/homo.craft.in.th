<?php
    if(!empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
?>
<center class="container pb-5">
    <div class="card mt-4" style="max-width:28rem">
        <div class="card-body">
        <?php
            if(empty($_GET['k'])){ ?>
			<img class="mt-3 mb-5 rounded mx-auto d-block" src="assets/logo_drite.svg" alt="Craft.in.th" width="200">
            <form class="text-left" onSubmit='ajax("forget_password",$(this).serialize(),"hcaptcha.reset()");return false;'>
                <div class="form-group">
                    <label><?= L::email?></label>
                    <input type="email" class="form-control" name="email" placeholder="<?= L::email?>">
                </div>
            <center>
            <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>"></div>
            </center>
                <button type="submit" class="btn btn-primary btn-block"><?= L::sendrest?></button>
            </form>
           <?php } else { ?>
			<img class="mt-3 mb-5 rounded mx-auto d-block" src="assets/logo_drite.svg" alt="Craft.in.th" width="200">
            <form class="text-left" onSubmit='ajax("reset_password",$(this).serialize());return false;'>
                    <input type="text" name="key" hidden value="<?= $_GET['k']?>">
                <div class="form-group">
                    <label><?= L::newpassword?></label>
                    <input type="password" class="form-control" name="password" placeholder="New passowrd">
                </div>
                <div class="form-group">
                    <label><?= L::confirmpassword?></label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm passowrd">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Reset password</button>
            </form>
           <?php }
        ?>
        
            <p class="my-3"><?= L::hasacc?> <a href="?page=home"><?= L::login?></a></p>
        </div>
    </div>
</center>