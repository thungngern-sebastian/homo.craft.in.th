<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
?>
<div class="container pb-5">
  <div class="card">
    <div class="card-body">
      <div class="row mx-auto">
        <div class="col-md-12">
          <form onSubmit='ajax("delete_user_next",$(this).serialize());return false;'>
            <h4><b><?= L::Delete_User?></b></h4>
              In case you wish to close your account You can contact the company by email support@craft.in.th. or request for account closure by any other method specified by the Company. <br><br>
              When your account is closed You agree and acknowledge that You will not be able to access your account. <br><br>
              May not exercise any rights in the account, including may not be able to use any services or products belonging to the company affiliates or business partners of the Company, subject to additional terms and conditions of use of such products. <br><br>
              A warning if the customer deletes their own data and will not be able to retrieve it again.<br><br>
            
            <!-- Button trigger modal -->
            <center>
              <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>"></div>
            </center>
            <button type="submit" class="btn btn-danger btn-block"><?= L::Delete_User_Comfirm?></button>
          </form>
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