<?php
  $setting=[];
  $query = _que('SELECT * FROM `setting`');
  if(!is_array($query) || @!isset($query['failed'])){
    $setting = $query->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<footer class="text-white" style="background-color:#111;flex-shrink: 0;">
  <div class="container py-4">
    <div class="row mt-5 mb-4 justify-content-center justify-content-md-start text-center text-md-left">
      <div class="col-md-4 mb-3 mb-md-0">
        <h4 class="font-weight-bold mb-3"><?= L::allservice?></h4>
        <a href="?page=deploy_cloud" class="text-white"><?= L::cloudserver?></a><br>
        <a href="?page=deploy_dedicated" class="text-white"><?= L::dedicated?></a><br>
        <a href="?page=deploy_colocation" class="text-white"><?= L::colocation?></a>
      </div>
      <div class="col-md-4 mb-3 mb-md-0 mb-4">
        <h4 class="font-weight-bold mb-3"><?= L::service?></h4>
        <a href="?page=term" class="text-white"><?= L::term?></a><br>
        <a href="?page=contact" class="text-white"><?= L::contact?></a>
      </div>
      <div class="col-md-4">
        <img class="mb-4" src="<?php echo $setting[0][logo_footer];?>" alt="controlpanel.craft.in.th" height="50">
        <br>
        <p><?= L::address?></p>
        <i class="fas fa-phone-alt mr-2"></i>064-661-6749
        <br>
        <i class="fas fa-envelope mr-2"></i>support@craft.in.th
        <br>
        <i class="fab fa-facebook mr-2"></i><a style="color:white;" href="https://www.facebook.com/craftstudioofficial">Craft.in.th</a>
      </div>
    </div>
  </div>
  <div style="background:#192035;">
    <div class="container px-lg-5 px-3 py-3 d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center">
    <p class="mb-0 d-flex justify-content-between"><i class="fal fa-copyright h5 mb-0 mr-2"></i><?= date('Y')?>, Craft.in.th</p>
      <div class="dropdown">
        <a class="btn btn-link d-flex align-items-center text-decoration-none" href="javascript:void()" data-toggle="dropdown">
          <i class="fal fa-language h5 mb-0 text-white"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item d-flex align-items-center" href="?page=<?=$_GET['page']?>&lang=th"><img src="assets/TH.png" width="20" class="mr-2"> ภาษาไทย</a>
          <a class="dropdown-item d-flex align-items-center" href="?page=<?=$_GET['page']?>&lang=en"><img src="assets/US.png" width="20" class="mr-2"> English</a>
        </div>
      </div>
    </div>
  </div>
</footer>
