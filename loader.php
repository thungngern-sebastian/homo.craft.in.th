<?php
  if(isset($_GET['logout'])){
    session_start();
    unset($_SESSION["username"]);
    unset($_SESSION["2fa"]);
    unset($_SESSION['sc_check']);
    
    die(header('Location: ?page=home'));
  }
  if(empty($_GET['page']) || !ctype_alnum(str_replace(['-', '_'], '', $_GET['page'])) || !file_exists("../pages/{$_GET['page']}.php")){
    die(header('Location: ?page=home'));
  }
  session_start();

  require_once '../API/i18n.class.php';
  $i18n = new i18n();
  $i18n->setCachePath('../cache');
  $i18n->setFilePath('../lang/{LANGUAGE}.ini');
  $i18n->setFallbackLang('en');
  $i18n->setSectionSeparator('_');
  $i18n->setMergeFallback(true);
  $i18n->init();
  require_once 'ess.php';
  $_GET=formee($_GET);
  if(!empty($_GET['lang']) && ($_GET['lang']=='en'||$_GET['lang']=='th')){
    $_SESSION['lang']=$_GET['lang'];
  }

  $is_admin=false;
  $is_activated=false;
  $is_suspended=false;
  if(!empty($_SESSION["username"])){
    $pdo= _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
      $user = $query->fetch(PDO::FETCH_ASSOC);
      if(empty($user)){
        unset($_SESSION["username"]);
        die(header('Location: ?page=home'));
      } else {
        $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
        $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
        $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
        if($is_suspended && !in_array($_GET['page'], ['suspended', 'contact', 'term'])){
          die(header('Location: ?page=suspended'));
        }
        if(!$is_activated && !in_array($_GET['page'], ['email_activation', 'contact', 'term'])){
          die(header('Location: ?page=email_activation'));
        }
        if((!empty($user['2fa']) && empty($_SESSION['2fa'])) && !in_array($_GET['page'], ['2fa_verify', 'contact', 'term'])){
          die(header('Location: ?page=2fa_verify'));
        }
        if($is_admin){
          $้tmp_['c']=0;
          $query = _que("SELECT count(ref1) as c FROM topup_history WHERE status=?",['Pending']);
          if(!is_array($query) || @!isset($query['failed'])){
              $้tmp_ = $query->fetch(PDO::FETCH_ASSOC);
          }
          $topup_pending=$้tmp_['c'];
        }
        }
    } else {
      unset($_SESSION["username"]);
      die(header('Location: ?page=home'));
    }
  }

  $setting=[];
  $query = _que('SELECT * FROM `setting`');
  if(!is_array($query) || @!isset($query['failed'])){
    $setting = $query->fetchAll(PDO::FETCH_ASSOC);
  }
  $pf_img=$setting[0]['logo'];
  $color = json_decode($setting[0]['color']);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/svg" href="<?php echo $setting[0]['icon'];?>">
  <link rel="stylesheet" href="assets/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="assets/fontawesome.min.css">
  <link rel="stylesheet" href="assets/solid.min.css">
  <link rel="stylesheet" href="assets/light.min.css">
  <link rel="stylesheet" href="assets/brands.min.css">
  <script src="assets/jquery-3.3.1.min.js"></script>
  <script src="assets/popper.min.js"></script>
  <script src="assets/bootstrap.min.js"></script>
  <script src="assets/js.cookie.min.js"></script>
  <script src="assets/colorize.js"></script>
  <script src="https://hcaptcha.com/1/api.js?hl=en" async defer></script>
  <script src="https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js"></script>
  <script src="assets/javascript.js"></script>
    <?= (isset($_COOKIE['scheme']))?'<link rel="stylesheet" href="assets/dark.css" type="text/css"/>':''?>
  <!-- Primary Meta Tags -->
  <title>Craft.in.th</title>
  <meta name="title" content="Craft">
  <meta name="description" content="บริการให้เช่า VPS - Dedicated Server ความเร็วสูงสำหรับองค์กรธุรกิจเซิร์ฟเวอร์ ทั้งในและต่างประเทศอย่างครบวงจร รายวัน รายเดือน">

  <meta name="robots" content="index, follow">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="language" content="English">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://controlpanel.craft.in.th/">
  <meta property="og:title" content="Craft">
  <meta property="og:description" content="บริการให้เช่า VPS - Dedicated Server ความเร็วสูงสำหรับองค์กรธุรกิจเซิร์ฟเวอร์ ทั้งในและต่างประเทศอย่างครบวงจร รายวัน รายเดือน
  ">
  <meta property="og:image" content="<?php echo $setting[0]['logo'];?>">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://controlpanel.craft.in.th/">
  <meta property="twitter:title" content="Craft - Cloud">
  <meta property="twitter:description" content="บริการให้เช่า VPS - Dedicated Server ความเร็วสูงสำหรับองค์กรธุรกิจเซิร์ฟเวอร์ ทั้งในและต่างประเทศอย่างครบวงจร รายวัน รายเดือน
  ">
  <meta property="twitter:image" content="<?php echo $setting[0]['logo'];?>">
  </head>
<?php echo "
  <style>
    :root{
      --bg:".$color->bg.";
      --primary: ".$color->primary.";
      --primary_hover: ".$color->primary_hover.";
      --fg:#222530;
      --vv:#0f111a;
      --font:#fff;
      --dropdown-item-font:var(--font);
      --input-bg:var(--bg);
      --input-border:rgba(0,0,0,.125);
      --dev:#fff;
      --intro-bg: #00b4db;
      --intro-bg: -webkitlinear-gradient(to right, #00b4db, #0083b0);
      --intro-bg: linear-gradient(to right, #00b4db, #0083b0);

      --hr: #fff;
      --no: #999999;
    }
    .intro {
      background-image: url(".$setting[0]['bg'].");  
      background-repeat: no-repeat;
      background-size: cover;
    }
	  .intro-x2 {
      background-image: url(".$setting[0]['bg'].");  
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>";
?>
<body style="min-height: 100vh;">

<div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
    FB.init({
      appId      : '1061786614275267',
      cookie     : true,
      xfbml      : true,
      version    : 'v6.0'
    });
      
  };
   <?php
    if(empty($_SESSION['username'])){
      ?>
    function fb_login() {
        FB.getLoginStatus(function(response) {
          ajax('fb_login',{'token': response['authResponse']['accessToken']});
        });
      }
      <?php
    } else {
      ?>
    $( document ).ready(function() {
    setInterval(fp, 600000);
    });
      <?php
    }
   ?>
    </script>
      <div class="fb-customerchat"
        attribution=setup_tool
        page_id="2245105559080265"
  theme_color="#20cef5"
  logged_in_greeting="สวัสดีค่ะ! เราช่วยอะไรคุณได้บ้างคะ? พิมพ์บอกพวกเราได้เลย"
  logged_out_greeting="สวัสดีค่ะ! เราช่วยอะไรคุณได้บ้างคะ? พิมพ์บอกพวกเราได้เลย">
      </div>
  <?php
    require_once"components/navbar.php";
  ?>
  <div id="output"></div>
  <?php
      if($_GET['page']=='home'&&empty($_SESSION['username'])){
        ?>
        <?php
      } else { ?>
        <div class="intro"></div>
  <?php }
  ?>
  <div class="mb-auto">
    <?php
      require_once"pages/{$_GET['page']}.php";
    ?>
  </div>
  <?php
    require_once"components/footer.php";
  ?>
</body>

</html>