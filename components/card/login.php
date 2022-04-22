
<center>
    <div class="card" style="max-width:28rem">
        <div class="card-body">
        <h2 class="font-weight-bold text-center mt-4 mb-4"><?= L::regismin?></h2>
        <form class="text-left my-3" onSubmit='ajax("login",$(this).serialize(),"hcaptcha.reset()");return false;'>
            <div class="form-group">
                <label><?= L::email?></label>
                <input type="email" class="form-control" placeholder="<?= L::email?>" name="email">
            </div>
            <div class="form-group">
                <label><?= L::password?></label>
                <input type="password" class="form-control" placeholder="<?= L::password?>" name="password">
            </div>
            <center>
            <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>"></div>
            </center>
            <button type="submit" class="btn btn-primary btn-block"><?= L::login?></button>
        </form>
<div class="fb-login-button" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="true" data-scope="public_profile" data-onlogin="fb_login();"></div>
            <p class="mt-2"><?= L::didnthavacc?> <a href="?page=register"><?= L::register?></a>, <a href="?page=forget"><?= L::forget?></a></p>
        </div>
    </div>
</center>