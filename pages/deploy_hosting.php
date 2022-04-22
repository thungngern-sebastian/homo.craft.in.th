<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }
?>
<script>
    let success = 0;
    let valid_domain_status = false;
    function validDomain(domain = null) {
        var status = false;
        if(domain == null) {
            return false;
        }
        let regex = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/g;
        if(domain.length >= 253) {
            $('[data-input="domain"]').val('');
            $('form#checkout input[name$="domain"]').val('');
            $('form#checkout h6#domain').text('');
            $('[data-alert="domain_invalid"]').show();
        } else if(regex.test(domain)) {
            $('form#checkout input[name$="domain"]').val(domain);
            $('form#checkout h6#domain').text(domain);
            $('[data-alert="domain_invalid"]').hide();
            status = true;
        } else if(domain.length >= 3 || domain.includes('.')) {
            $('[data-alert="domain_invalid"]').show();
        }
        if(valid_domain_status != status) {
            valid_domain_status = status;
            if(status == true) {
                success++;
            } else {
                success--;
            }
            checkSuccess();
        }
    }

    function length(e, l) {
        var lpar = $(e).parent();
        $('#lenght_selector').children().each(function(){
            $(this).children().removeClass("border-primary");
            $(this).children().removeClass("text-primary");
            $(this).children().children().find('small').text('Select version');
        });
        $(lpar).addClass('border-primary text-primary');
        $('form#checkout input[name$="length"]').val(l);
        $('form#checkout h6#length').text(l + ' Days');
        $('form#checkout h6#plan').text('');
        $('form#checkout h6#price').text('');
        $('form#checkout h6#domain').text('');
        $('form#checkout input[name$="plan"]').val('');
        $('form#checkout input[name$="domain"]').val('');
        load('fetch_hosting', 'length='+l, '#fetch_result');
        success = 1;
        checkSuccess();
    }

    function plan(e, pl = 1, p = 0) {
        var ppar = $(e).parent();
        $('#plan_selector').children().each(function(){
            $(this).children().removeClass("border-primary");
            $(this).children().removeClass("text-primary");
            $(this).children().children().find('small').text('Select version');
        });
        $(ppar).addClass('border-primary text-primary');
        $('form#checkout input[name$="plan"]').val(pl);
        $('form#checkout h6#plan').text('<?= L::plan?> ' + pl);
        $('form#checkout h6#price').text(p);
        success++;
        checkSuccess();
    }

    function checkSuccess() {
        if(success >= 3) {
            return $('[data-button="deploy"]').attr('disabled', false);
        } else {
            return $('[data-button="deploy"]').attr('disabled', true);
        }
    }

    function deploy(f) {
        form = $(f);
        c = form.serialize();
        $('#setup').hide();
        $('#loadingscene').show();
        $("input button a").prop("disabled", true);
        $.post("a.php?a=deploy_hosting", c, function(data) {
            if(typeof data.msg !== 'undefined'){
                $('#status').html('<h1 class="mb-3"><i class="fal fa-check"></i></h1>')
                $('#return_msg').text(data.msg)
                window.location.replace(data.url)
            }
        },'json').fail(function(xhr) {
            try{ var data = $.parseJSON(xhr.responseText) }
            catch(err)
            { data={} }
            $('#status').html('<img class="mb-4" src="assets/fail.png" style="max-width:200px;">')
            //$('#return_msg').text('Fail To process due to : '+data.msg)
			$('#return_msg').html('<h4 class="mb-1"><b>Fail To Process</b></h4>'+data.msg)
        }).always(function() {
            $("input button a").prop("disabled", false);
        });
    }
</script>
<div class="container pb-5">
<div class="card mb-4" id="loadingscene" style='display:none;'>
    <div class="card-body">
        <center>
            <div id="status">
                <div class="spinner-border text-primary mt-3 mb-4">
                    <span class="sr-only"></span>
                </div>
            </div>
            <p class="mb-0" id="return_msg">กำลังทำงานอยู่<br>อย่าปิดหน้าต่างนี้</p>
        </center>
    </div>
</div>
<div class="row" id="setup">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
            <h4><i class="fas fa-server mr-2"></i>Hosting</h4>
                <hr>
                <h5><?= L::lenght?></h5>
                <div class="row" id="lenght_selector">
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="length($(this), 1)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fal fa-calendar-day h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">1 <?= L::day?></p>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="length($(this), 30)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fal fa-calendar-week h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">30 <?= L::day?></p>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="length($(this), 365)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fal fa-calendar-alt h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">365 <?= L::day?></p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="fetch_result"></div>

            <!--
                <h5><?= L::plan?></h5>
                <div class="row" id="plan_selector">
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="plan($(this), 1, 100)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fas fa-server h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">Plan 1</p>
                                    <p style="font-size:12px; line-height:14px;" class="mb-0 mt-2">
                                        <i class="fal fa-globe"></i> 1 Domain</br>
                                        <i class="fas fa-database"></i> 1 Database</br>
                                        <i class="fas fa-envelope"></i> Unlimit Email</br>
                                        <i class="fal fa-hdd"></i> 5 GB
                                    </p>
                                    <div class="px-1 py-2 text-center">
                                        <p class="mb-0">100฿ / <?= L::month?></p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="plan($(this), 2, 150)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fas fa-server h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">Plan 2</p>
                                    <p style="font-size:12px; line-height:14px;" class="mb-0 mt-2">
                                        <i class="fal fa-globe"></i> 2 Domain</br>
                                        <i class="fas fa-database"></i> 2 Database</br>
                                        <i class="fas fa-envelope"></i> Unlimit Email</br>
                                        <i class="fal fa-hdd"></i> 10 GB
                                    </p>
                                    <div class="px-1 py-2 text-center">
                                        <p class="mb-0">150฿ / <?= L::month?></p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="plan($(this), 3, 200)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fas fa-server h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">Plan 3</p>
                                    <p style="font-size:12px; line-height:14px;" class="mb-0 mt-2">
                                        <i class="fal fa-globe"></i> 3 Domain</br>
                                        <i class="fas fa-database"></i> 3 Database</br>
                                        <i class="fas fa-envelope"></i> Unlimit Email</br>
                                        <i class="fal fa-hdd"></i> 15 GB
                                    </p>
                                    <div class="px-1 py-2 text-center">
                                        <p class="mb-0">200฿ / <?= L::month?></p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="plan($(this), 4, 250)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fas fa-server h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">Plan 4</p>
                                    <p style="font-size:12px; line-height:14px;" class="mb-0 mt-2">
                                        <i class="fal fa-globe"></i> 4 Domain</br>
                                        <i class="fas fa-database"></i> 4 Database</br>
                                        <i class="fas fa-envelope"></i> Unlimit Email</br>
                                        <i class="fal fa-hdd"></i> 20 GB
                                    </p>
                                    <div class="px-1 py-2 text-center">
                                        <p class="mb-0">250฿ / <?= L::month?></p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <h5>Domain</h5>
                <div class="form-group">
                    <label>Domain <span data-alert="domain_invalid" style="display: none;" class="badge badge-pill badge-danger">Domain invalid</span></label>
                    <input type="text" class="form-control" oninput="validDomain($(this).val())" data-input="domain" spellcheck="false" data-ms-editor="true" autocomplete="off">
                </div>
            -->


            </div>                  
        </div>
    </div>
    <div class="col-lg-4"> 
        <div class="card sticky mb-3">
            <div class="card-body pb-0">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Checkout</h5>
            </div>
            <hr>
            <form id="checkout" onsubmit="deploy($(this));return false;">
                <input name="length" type="hidden">
                <input name="plan" type="hidden">
                <input name="domain" type="hidden">
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between pt-2">
                        <h6><b><i class="fas fa-calendar-alt mr-2"></i><?= L::lenght?></b></h6>
                        <h6 id="length"></h6>
                    </div>
                    <div class="d-flex justify-content-between pt-2">
                        <h6><b><i class="fal fa-globe mr-2"></i>Domain</b></h6>
                        <h6 id="domain" style="max-width: 150px;"></h6>
                    </div>
                    <div class="d-flex justify-content-between pt-2">
                        <h6><b><i class="fas fa-server mr-2"></i><?= L::plan?></b></h6>
                        <h6 id="plan"></h6>
                    </div>
                    <div class="d-flex justify-content-between pt-2">
                        <h6><b><i class="fas fa-box-open mr-2"></i><?= L::price?></b></h6>
                        <h6 id="price"></h6>
                    </div>
                    <hr>
                    <button class="btn btn-primary btn-block btn-lg mt-2" data-button="deploy" disabled><?= L::rentcloudbtn?></button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

