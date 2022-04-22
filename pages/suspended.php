<?php
if(empty($_SESSION['username']) || !$is_suspended){
    die('<script>window.location.replace("?page=home")</script>');
}
?>
<center class="container pb-5">
<div class="card" style="max-width:28rem">
    <div class="card-body text-center">
        <i class="fal fa-treasure-chest display-3"></i>
        <p class="mb-2">บัญชีของท่านถูกระงับการใช้งาน<br>กรุณาอ่านข้อตกลง การใช้งานบริการของเรา หรือ</p>
        <a href="https://m.me/controlpanel.craft.in.th" target="_blank" class="btn btn-primary btn-block">ติดต่อ support</a>
    </div>
</div>
</center>