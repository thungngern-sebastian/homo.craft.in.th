<?php
if(empty($_SESSION['username'])) {
    die('<script>window.location.replace("?page=home")</script>');
}
?>

<script>
    function validGift(domain = null) {
        $('div#TW_platform [data-button="topup_tw"]').attr('disabled', true);
        if(domain == null) {
            return false;
        }
        let regex_id = /^[A-Za-z0-9]+$/g;
        if(domain.startsWith('https://gift.truemoney.com/campaign/?v=')) {
            var domain = (new URL(domain));
            var result = null;
            var tmp = [];
            domain.search.substr(1).split("&").forEach(function (item) {
                tmp = item.split("=");
                if (tmp[0] === 'v') {
                    result = decodeURIComponent(tmp[1]);
                }
            });
            if(!result || result.length !== 18 || !regex_id.test(result)) {
                $('[data-alert="gift_invalid"]').show();
            } else {
                $('input[name="tw_voucher_id"]').val(result);
                $('[data-alert="gift_invalid"]').hide();
                return $('div#TW_platform [data-button="topup_tw"]').attr('disabled', false);
            }
        } else {
            $('[data-alert="gift_invalid"]').show();
        }
    }
</script>
<div class="container pb-5">
    <script src="https://www.paypal.com/sdk/js?client-id=AdhdkCSw1AttKZ2oYaN_mxy2n6iPluBBNq2YR98ZiUA3qFgptiqdoroWk0BcyaDsdGJqIklbNHP2liV0&currency=THB&disable-funding=credit,card" data-sdk-integration-source="button-factory"></script>

    <form id="checkout" onSubmit="ajax('topup', $(this).serialize(),'hcaptcha.reset()');return false;">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                    <button class="btn btn-link btn-block text-reset text-decoration-none" type="button" data-toggle="collapse" data-target="#option" >
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-2"><?= L::paymentoption?></h4>
                            <i class="fal fa-chevron-down"></i>
                        </div>
                    </button>
                        <div class="collapse show" id="option">
                            <center>
                        <div class="list-group list-group-flush text-md-left">
                            <button type="button" onclick="$('select[name$=\'pay_option\']').val('KB').trigger('change');" class="list-group-item list-group-item-action py-2">
                                <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-start">  
                                    <div class="mr-md-3">
                                        <img src="https://controlpanel.craft.in.th/assets/payment/kbank.png" width="46">
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><b>Kasikorn Bank</b></h5>
                                        <p class="mb-0"><?= L::pnttwtdacuabtti?></p>
                                    </div>
                                </div>
                            </button>
                            <button type="button" onclick="$('select[name$=\'pay_option\']').val('TW').trigger('change');" class="list-group-item list-group-item-action py-2">
                                <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-start">
                                    <div class="mr-md-3">
                                        <img src="https://controlpanel.craft.in.th/assets/payment/wallet.svg" width="46">
                                    </div>
                                    <div class="align-items-center">
                                        <h5 class="mb-0"><b>TrueWallet</b></h5>
                                        <p class="mb-0"><?= L::pnttwtdacuabtti?></p>
                                    </div>
                                </div>
                            </button>
                        </div>
                        </center>
                        </div>
                        <hr class="mt-0">
                        <div id="KB_platform" class="xclrp" style="display:none;">
                            <img src="assets/kb_qr.jpg" style="width:100%;max-width:300px;" class="rounded mx-auto d-block mb-5 mt-5">   
                            <h3 class="text-center mt-5">เลขที่บัญชี : 028-8-65961-3</h3>
                            <h5 class="text-center mb-5">ชื่อบัญชี : นาย ปิยวัฒน์ นิตยกัญจน์</h5>
                            <div class="alert alert-primary">เมื่อชำระเงินเสร็จสิ้นสามารถแจ้งขำระได้ที่เพจ Facebook Craft.in.th ได้ตลอด 24 ชั่วโมง</div>
                            <a class="btn btn-primary btn-block btn-lg" href="https://www.facebook.com/craftstudioofficial/inbox">แจ้งชำระเงิน</a>
                        </div>
                        <div id="TW_platform" class="xclrp" style="display:none;">
                            <!--
                            <img src="assets/tw_qr.jpg" style="width:100%;max-width:300px;" class="rounded mx-auto d-block mb-5 mt-5">
                            <h3 class="text-center mt-5">เบอร์ Wallet : 064-661-6749</h3>
                            <h5 class="text-center mb-5">ชื่อบัญชี : นาย ปิยวัฒน์ นิตยกัญจน์</h5>
                            <div class="alert alert-primary">เมื่อชำระเงินเสร็จสิ้นสามารถแจ้งขำระได้ที่เพจ Facebook controlpanel.craft.in.th ได้ตลอด 24 ชั่วโมง</div>
                            -->
							<img src="assets/tw_how.png" style="width:100%;max-width:1200px;" class="rounded mx-auto d-block mb-5 mt-5">
                            <h5>ลิ้งก์ซองอั่งเปา</h5>
                            <div class="form-group">
                                <label>ลิ้งก์ซองอั่งเปา <span data-alert="gift_invalid" style="display: none;" class="badge badge-pill badge-danger">ลิ้งก์ไม่ถูกต้อง</span></label>
                                <input type="text" class="form-control" oninput="validGift($(this).val())" spellcheck="false" data-ms-editor="true" autocomplete="off">
                            </div>
                            <input type="hidden" name="tw_voucher_id">
                            <div class="h-captcha text-center" data-sitekey="<?= $_HCAP_SK?>"></div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg" data-button="topup_tw">เติมเงิน</button>
                        </div>
                    </div>
                </div>         
            </div>
            <div class="col-lg-4"> 
                <div class="card sticky mb-3">
                    <div class="card-body pb-0">
                        <h5 class="mb-0"><?= L::summary?></h5>
                    </div>
                    <hr>
                    <select name="pay_option" hidden>
                        <option></option>
                        <option value="KB">Kasikorn Bank</option>
                        <option value="TW">TrueWallet</option>
                    </select>
                    <div class="card-body pt-2">
                        <div class="d-flex justify-content-between">
                            <h6><b><i class="fas fa-cubes mr-2"></i><?= L::summarytype?></b></h6>
                            <h6 id="type_c"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="amount" value="20">
    </form>
</div>
<script>
    $('select[name$="pay_option"]').change(function() {    
        var data = $(this).val(); 
        $('.xclrp').each(function() {
            $(this).hide();
        });
        $('#' + data + '_platform').show();
        $('#amount_box').show();
        $('#type_c').text($("select[name$=\"pay_option\"] option:selected").text());
        if(data == 'PP') {
            $('#topup_btn').hide();
        } else {
            $('#topup_btn').show();
        }
        $('#option').collapse('toggle')
    });
</script>
