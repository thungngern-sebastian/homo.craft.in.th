<?php
    if(empty($_SESSION['username'])){
        die('<script>window.location.replace("?page=home")</script>');
    }

?>
<script>
    function os(f) {
        par=$(f).parent().parent();
        $('#os_selector').children().each(function(){

            $( this ).children().removeClass( "border-primary" );
            $( this ).children().removeClass( "text-primary" );
            $( this ).children().find(">:first-child").children().removeClass('border-primary');
            $( this ).children().children().find('small').text('Select version');
        });
        $('form#checkout input[name$="os"]').val($(par).find('p').text());
        $('form#checkout input[name$="distro"]').val($(f).text());
        $('#plan-os').text($(par).find('p').text());
        $('#plan-distro').text($(f).text());
        $(par).addClass('border-primary text-primary');
        $(par).find(">:first-child").children().addClass('border-primary');
        $(par).find('small').text($(f).text());
        check_can_submit();
    }
    function price(){
        var b=$('form#checkout #base_price').val();
       var price=0;
        if(b!=0){
            price = b* $('form#checkout input[name$="lenght"]').val();
        } else {
            cpu_price= 6.7 *$('form#checkout input[name$="cpu"]').val();
            ram_price= 3.4 *$('form#checkout input[name$="ram"]').val();
            disk_price= 0.07 *$('form#checkout input[name$="disk"]').val();
            base_price=cpu_price+ram_price+disk_price;
            price=base_price*$('form#checkout input[name$="lenght"]').val();
        }
            $('form#checkout #plan-price').text(price+'฿');
    }
    function lenght(f,d=1) {
        par=$(f).parent();
        $('#lenght_selector').children().each(function(){

            $( this ).children().removeClass( "border-primary" );
            $( this ).children().removeClass( "text-primary" );
            $( this ).children().children().find('small').text('Select version');
        });
        $(par).addClass('border-primary text-primary');
        $('form#checkout input[name$="lenght"]').val(d);
        $('form#checkout h6#plan-lenght').text(d+' <?= L::day?>');
        load('fetch_cloud_deploy','lenght='+d,'#fetch_result');
        check_can_submit();
    }
    function check_can_submit(){
        cpux=$('form#checkout input[name$="cpu"]').val();
        ramx=$('form#checkout input[name$="ram"]').val();
        diskx=$('form#checkout input[name$="disk"]').val();
        lenghtx=$('form#checkout input[name$="lenght"]').val();
        osx=$('form#checkout input[name$="os"]').val();
        distrox=$('form#checkout input[name$="distro"]').val();
        planx=$('form#checkout input[name$="plan"]').val();
        if(cpux!='' &&ramx!='' &&diskx!='' &&lenghtx!='' &&distrox!='' &&osx!='' &&planx!=''){
        $('form#checkout #submito_button_OwO').prop("disabled", false);
        } else {
        $('form#checkout #submito_button_OwO').prop("disabled", true);
        }
        
    }
    function plan(f) {
        
        cpu($(f).data('cpu'))
        ram($(f).data('ram'))
        hdd($(f).data('disk'))
        $(f).children().addClass('border-primary text-primary');
        $('form#checkout #base_price').val($(f).data('bp'));
        $('form#checkout input[name$="plan"]').val($(f).data('plan'));
        price();
        check_can_submit();
    }

    function cpu(x=1) {
        $('#plan_selector').children().each(function(){
            $( this ).children().children().removeClass( "border-primary" );
            $( this ).children().children().removeClass( "text-primary" );
        });
        $('#cpu_custom').val(x);
        $('form#checkout input[name$="plan"]').val('Custom');
        $('form#checkout #base_price').val('0');
        $('form#checkout input[name$="cpu"]').val(x);
        $('form#checkout h6#plan-cpu').text(x+' Core');
        $('#pr-cpu').text(x+' Core');
        price();
        check_can_submit();
    }

    function ram(x=1) {
        $('#plan_selector').children().each(function(){
            $( this ).children().children().removeClass( "border-primary" );
            $( this ).children().children().removeClass( "text-primary" );
        });
        $('#ram_custom').val(x);
        $('form#checkout input[name$="plan"]').val('Custom');
        $('form#checkout #base_price').val('0');
        $('form#checkout input[name$="ram"]').val(x);
        $('form#checkout h6#plan-ram').text(x+' GB');
        $('#pr-ram').text(x+' GB');
        price();
        check_can_submit();
    }

    function hdd(x=<?=$disk_minimumn?>) {
        $('#plan_selector').children().each(function(){
            $( this ).children().children().removeClass( "border-primary" );
            $( this ).children().children().removeClass( "text-primary" );
        });
        $('#hdd_custom').val(x);
        $('form#checkout input[name$="plan"]').val('Custom');
        $('form#checkout #base_price').val('0');
        $('form#checkout input[name$="disk"]').val(x);
        $('form#checkout h6#plan-hdd').text(x+' GB');
        $('#pr-hdd').text(x+' GB');
        price();
        check_can_submit();
    }
    function deploy(f){
        form = $(f);
        c = form.serialize();
        cpux=$('form#checkout input[name$="cpu"]').val();
        ramx=$('form#checkout input[name$="ram"]').val();
        diskx=$('form#checkout input[name$="disk"]').val();
        lenghtx=$('form#checkout input[name$="lenght"]').val();
        osx=$('form#checkout input[name$="os"]').val();
        distrox=$('form#checkout input[name$="distro"]').val();
        planx=$('form#checkout input[name$="plan"]').val();
        if(cpux!='' &&ramx!='' &&diskx!='' &&lenghtx!='' &&distrox!='' &&osx!='' &&planx!=''){
            $('#setup').hide();
            $('#loadingscene').show();
            $("input button a").prop("disabled", true);
            $.post( "a.php?a=deploy_cloud", c, function(data) {
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
            <h4><i class="fas fa-server mr-2"></i><?= L::cloudserver?></h4>
                <hr>
                <h5><?= L::lenght?></h5>
                <div class="row" id="lenght_selector">
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="lenght($(this))">
                                <div class="px-1 py-2 text-center">
                                    <i class="fal fa-calendar-day h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">1 <?= L::day?></p>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="lenght($(this),7)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fal fa-calendar-week h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">7 <?= L::day?></p>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box mb-2">
                            <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="lenght($(this),30)">
                                <div class="px-1 py-2 text-center">
                                    <i class="fal fa-calendar-alt h1 mb-0"></i>
                                    <p class="mb-0" style="font-size:12px;">30 <?= L::day?></p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="fetch_result"></div>
            </div>                  
        </div>
    </div>
    <div class="col-lg-4"> 
        <div class="card sticky mb-3">
            <div class="card-body pb-0">
                <h5 class="mb-0"><?= L::lenght?></h5>
            </div>
            <hr>
            <form id="checkout" onsubmit="deploy($(this));return false;">
                <input name="lenght" type="number" hidden>
                <input name="os" type="text" hidden>
                <input name="distro" type="text" hidden>
                <input name="plan" type="text" hidden>
                <input name="cpu" type="number" hidden>
                <input name="ram" type="number" hidden>
                <input name="disk" type="number" hidden>
                <input name="name" type="text" hidden>
                <input id="base_price" type="number" hidden>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between pt-2">
                        <h6><b><i class="fas fa-calendar-alt mr-2"></i><?= L::lenght?></b></h6>
                        <h6 id="plan-lenght"></h6>
                    </div>
                    <div id="checkout-spec" style="display:none;">
                    <hr>
                        <div class="d-flex justify-content-between pt-2">
                            <h6><b><i class="fas fa-microchip mr-2"></i><?= L::cpu?></b></h6>
                            <h6 id="plan-cpu"></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6><b><i class="fas fa-memory mr-2"></i><?= L::ram?></b></h6>
                            <h6 id="plan-ram"></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6><b><i class="fas fa-hdd mr-2"></i><?= L::disk?></b></h6>
                            <h6 id="plan-hdd"></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6><b><i class="fas fa-desktop mr-2"></i><?= L::os?></b></h6>
                            <h6 id="plan-os"></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6><b><i class="fas fa-box-open mr-2"></i><?= L::distro?></b></h6>
                            <h6 id="plan-distro"></h6>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between aligns-item-center">
                            <h6><b><i class="fas fa-box-open mr-2"></i> <?= L::price?></b></h6>
                            <h5 id="plan-price"></h5>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg mt-2" id="submito_button_OwO" disabled><?= L::rentcloudbtn?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

