<?php
if(empty($_SESSION['username'])){
    die('<script>window.location.replace("?page=home")</script>');
}
?>
<script>
    function em_(tokenx=''){
        ajax('email_activation',{'h-captcha-response':tokenx})
    }
</script>
<center class="cotainer pb-5">
<div class="card" style="max-width:28rem">
    <div class="card-body text-center" >
            <?php
            if($is_activated){
                ?>
                    <i class="fal fa-envelope display-3"></i>
                    <p class="mb-0">ยืนยันอีเมลสำเร็จ</p>
                <?php
            } else { 
                if(empty($_GET['k'])){ ?>
                    <i class="fal fa-envelope-open-text display-3 mb-4"></i>
                    <p class="mb-4">กรุณายืนยันบัญชีของคุณ<br>ก่อนใช้งานบริการของเรา</p>
                    <center>
                        <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>" data-callback	="em_"></div>
                    </center>
                <?php } else { 
                    if($_GET['k'] === $user['activate_key']){
                        _que('UPDATE customer SET is_activated=1 WHERE id=?',[$_SESSION['username']]);
                        ?>
                    <i class="fal fa-envelope-open-text display-3 text-success"></i>
                    <p class="mb-2">ยืนยันตัวตนสำเร็จ</p>
                    <a class="btn btn-primary px-5" href="?page=home">กลับหน้าหลัก</a>
                <?php } else { ?>
                    <i class="fal fa-envelope-open-text display-3 text-danger"></i>
                    <p class="mb-2">ยืนยันตัวตนไม่สำเร็จ</p>
                    <a class="btn btn-primary px-5" href="?page=email_activation">ยืนยันใหม่อีกครั้ง</a>
                <?php }
                }
                    ?>
            <?php }
            ?>

    </div>
</div>
</center>