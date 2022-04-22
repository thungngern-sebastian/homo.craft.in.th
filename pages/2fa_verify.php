<?php
    if(empty($user['2fa']) || !empty($_SESSION['2fa'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
?>
<div class="container pb-5">
<center>
    <div class="card mt-4" style="max-width:28rem">
        <div class="card-body">
            <h1><i class="fal fa-key"></i></h1>
            <h4>กรุณายืนยัน 2FA</h4>
            <form class="text-left" onSubmit='ajax("2fa_verify",$(this).serialize());return false;'>
                <div class="form-group">
                    <label>2FA code</label>
                    <input type="text" class="form-control" name="2fa">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Veirfy</button>
            </form>
        </div>
    </div>
</center>
</div>