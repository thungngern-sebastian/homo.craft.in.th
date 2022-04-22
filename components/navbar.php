<?php
$setting=[];
$query = _que('SELECT * FROM `setting`');
if(!is_array($query) || @!isset($query['failed'])){
  $setting = $query->fetchAll(PDO::FETCH_ASSOC);
}
if(empty($_COOKIE['COOKIE'])){ ?>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
  <div class="alert alert-dark fade show fixed-bottom mb-0">
    <div class="container">
      <div class="row text-center d-flex flex-column flex-row justify-content-center align-items-center">
          <h1><i class="fal fa-cookie-bite mr-2 mb-2"></i></h1> <?= L::cookie?>
          <button type="button" class="btn btn-dark px-4 btn-sm mt-3" data-dismiss="alert" onclick="Cookies.set('COOKIE', 'yummy')">
          <?= L::accept?>
          </button>
      </div>
    </div>
  </div>
<?php } ?>
<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
    <div class="container px-lg-5 px-3">
      <a class="navbar-brand mr-1" href="?page=home"><img src="<?php echo $setting[0][logo];?>" height="50"></a>
      <button class="btn btn-link text-reset text-decoration-none d-lg-none" data-toggle="collapse" data-target="#nav">
        <i class="fal fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <?php
      if(!empty($_SESSION['username'])){
          ?>
        <ul class="navbar-nav">
          <li class="nav-item px-1">
            <a class="nav-link" href="?page=home"><i class="fas fa-home mr-2"></i> <?= L::home?></a>
          </li>
          <li class="nav-item dropdown px-1">
            <a class="nav-link dropdown-toggle" href="javascript:void()" data-toggle="dropdown">
            <i class="fas fa-store-alt mr-2"></i> <?= L::deploy?>
            </a>
            <div class="dropdown-menu">
              <span class="dropdown-item-text px-3" style="font-size:14px;"><i class="fas fa-clouds mr-2"></i>Main Product</span>
                <a class="dropdown-item" href="?page=deploy_cloud"><i class="fas fa-desktop mr-2"></i>Cloud Server</a>
                <a class="dropdown-item" href="?page=deploy_hosting"><i class="fas fa-cloud mr-2"></i>Hosting</a>
              <div class="dropdown-divider"></div>
              <span class="dropdown-item-text px-3" style="font-size:14px;"><i class="fas fa-cubes mr-2"></i>Other</span>
              <a class="dropdown-item" href="?page=deploy_dedicated"><i class="fas fa-server mr-2"></i><?= L::dedicated?></a>
              <a class="dropdown-item" href="?page=deploy_colocation"><i class="fas fa-archive mr-2"></i><?= L::colocation?></a>
            </div>
          </li>
          <li class="nav-item px-1">
            <a class="nav-link" href="?page=topup"><i class="fas fa-wallet mr-2"></i> <?=L::topup?></a>
          </li>
          <li class="nav-item px-1">
            <a class="nav-link" href="?page=contact"><i class="fas fa-id-card-alt mr-2"></i> <?= L::contact?></a>
          </li>
        </ul>
        <?php
      }
      ?>
        <ul class="navbar-nav ml-auto flex-row">
          <li class="nav-item">
            <a class="nav-link pr-0 d-flex" href="?page=help"><i class="fas fa-question-circle h5 mb-0 mr-1 mr-2"></i> <?= L::help_center?></a>
          </li>
		  <!-- <li class="nav-item">
          	<a href="javascript:void()" onClick="scheme()" class="nav-link pr-0 d-flex"><i class="fal fa-cloud-moon h5 mb-0 mr-1"></i></a>
		  </li> -->
		</ul>
		<ul class="navbar-nav flex-row">
        <?php
          if($is_admin){
              ?>
                  <li class="nav-item dropdown px-1">
                    <a class="nav-link dropdown-toggle" href="javascript:void()" data-toggle="dropdown">
                    <i class="fas fa-users-cog mr-2"></i>Admin <?= ($topup_pending>0)?'<span class="badge badge-light">'.$topup_pending.'</span>':''?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item" href="?page=b_setting"><i class="fas fa-cog mr-2"></i>Setting</a>
				 	            <a class="dropdown-item" href="?page=b_pages"><i class="fas fa-window-maximize mr-2"></i>Page</a>
                      <a class="dropdown-item" href="?page=b_hosts"><i class="fas fa-server mr-2"></i>Hosts</a>
                      <a class="dropdown-item" href="?page=b_templates"><i class="fas fa-copy mr-2"></i>Templates</a>
                      <a class="dropdown-item" href="?page=b_plans"><i class="fab fa-telegram-plane mr-2"></i>Plans</a>
                      <a class="dropdown-item" href="?page=b_arcticles"><i class="fas fa-book mr-2"></i>Article</a>
                      <a class="dropdown-item" href="?page=b_ip"><i class="fas fa-map-pin mr-2"></i>IP Address</a>
                      <a class="dropdown-item" href="?page=b_vm"><i class="fas fa-hdd mr-2"></i>Virtual Machine</a>
                      <a class="dropdown-item" href="?page=b_customer"><i class="fas fa-users mr-2"></i>Customer</a>
                      <a class="dropdown-item" href="?page=b_log"><i class="fas fa-paperclip mr-2"></i>Logs</a>
					  </div>
                  </li>
              <?php
          }
              ?>
		 </ul>
         <ul class="navbar-nav align-items-center">
          <li class="nav-item py-0 border-left d-none d-lg-block h-100 ml-2 mr-2">
            <span class="navbar-text"> </span>
          </li>
          <?php
          if(!empty($_SESSION['username'])){
              ?>
               <li class="nav-item dropdown" border-left>
                 <a class="nav-link"  href="javascript:void()" data-toggle="dropdown">
                  <div class="d-flex flex-row align-items-center justify-content-center">
                      <div class="text-right mr-2" style="line-height:12px;"><?= L::balance?><br>
                      <span class="text-primary">฿<span id="user_point"><?= number_format($user['point'], 2)?></span></span></div>
                      <img src="<?= (!empty($user['pf_img'])?$user['pf_img']:$pf_img)?>" width="32" height="32" class="rounded-circle">
                  </div>
                 </a>
                 <div class="dropdown-menu dropdown-menu-right">
                    <p class="text-nowrap mb-0 mr-5 px-3"><?= $user['fname']?> <?= $user['lname']?></p>
                    <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="?page=order_history"><i class="fas fa-history mr-2"></i><?=L::orderhistory?></a>
                   <!-- <a class="dropdown-item" href="?page=topup_history"><i class="fal fa-history"></i><?=L::topuphistory?></a> -->
                   <a class="dropdown-item" href="?page=setting"><i class="fas fa-sliders-v-square mr-2"></i><?=L::setting?></a>
                   <a class="dropdown-item" href="?logout"><i class="fas fa-sign-out mr-2"></i><?=L::logout?></a>
                 </div>
               </li>
             <?php
            } else {
               ?>
             <li class="nav-item px-2">
               <a class="btn btn-outline-primary px-4 btn-sm" href="?page=register"><i class="fal fa-user-plus"></i> <?= L::register?></a>
             </li>
             <?php
            }
           ?>
           <li class="nav-item">
               <a class="nav-link" href="javascript:void()" onClick="scheme()"><i class="fal fa-cloud-<?= (isset($_COOKIE['scheme']))?'moon':'sun'?> h5 mb-0" id="icon_mode"></i></a>
             </li>
      </ul>
    </div>
	</div>
  </nav>