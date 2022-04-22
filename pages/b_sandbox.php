<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    ?>

<div class="container pb-5">
<div class="card">
    <div class="card-body">
        <h4>Sandbox for kiddo trying some weird shit</h4>
            <form onSubmit='ajax("b_email_test",$(this).serialize());return false;'>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send test email</button>
            </form>
            <hr>
        <h4>Paypal w/ Sandbox OwO</h4>
        <script src="https://www.paypal.com/sdk/js?client-id=Ae0-Qo2EMUDQocDicgt_shO8E8nhod5WW6DN2n_xyje1wP5zAAchmOZFKRoIMYN2rDw3iNrtRIYkiEGH&currency=THB&disable-funding=credit,card" data-sdk-integration-source="button-factory"></script>

            <form id="checkout">
                <div class="form-group">
                    <label>TYPE</label>
                    <input type="text" name="pay_option" value="PP" class="form-control" readonly>
                </div>
                    <input type="text" name="sandbox_mode" value="wwwwww" readonly hidden>
                <div class="form-group">
                    <label>PP ID</label>
                    <input type="text" name="PP-id" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>AM</label>
                    <input type="text" name="amount" class="form-control">
                </div>
                <center class="mb-3">
                    <div class="h-captcha" data-sitekey="<?= $_HCAP_SK?>"></div>
                    
                    </center>
                    <div id="paypal-button-container"></div>
            </form>
            <hr>
    </div>
</div>
</div>
<script>
  paypal.Buttons({
    style: {
        shape: 'rect',
        color: 'blue',
        layout: 'vertical',
        label: 'paypal',
    },
    createOrder: function(data, actions) {
    var x = $('input[name$="amount"]').val();
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: x
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      return actions.order.capture().then(function(details) {
        $('input[name$="PP-id"]').val(details.id)
        ajax('topup', $('#checkout').serialize());
      });
    }
  }).render('#paypal-button-container');
</script>