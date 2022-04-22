function ajax(s,c=[],a=''){
    swal('info', 'Loading', 'spinner fa-spin', '');
    $("input button a").prop("disabled", true);
    $.post( "a.php?a="+s, c, function(data) {
        if(typeof data.msg !== 'undefined'){
            swal('success', data.msg, 
                (typeof data.icon !== 'undefined')?data.icon:'check',
                (typeof data.eval !== 'undefined')?data.eval:'',a);
        }
    },'json').fail(function(xhr) {
        try{ var data = $.parseJSON(xhr.responseText) }
        catch(err)
        { data={} }
        swal('danger', (typeof data.msg !== 'undefined')?data.msg:'Error! something is wrong with data', 
            (typeof data.icon !== 'undefined')?data.icon:'times',
            (typeof data.eval !== 'undefined')?data.eval:'',a);
    }).always(function() {
        $("input button a").prop("disabled", false);
    });
}
function load(s,c='',a=''){
    swal('info', 'Loading', 'spinner fa-spin', '');
    $("input button a").prop("disabled", true);
    $(a).load( "a.php?a="+s+"&"+c, function() {
        $('#r_s').fadeOut("normal", function() {
            $(this).remove();
            $("input button a").prop("disabled", false);
        });
    });
}

function swal(status, msg, icon='times', evalx='',a=''){
    $('#output').html(
    `<div class="alert alert-`+status+` p-0 d-flex align-items-stretch mb-0 border-0 rounded-0 fixed-top fade show" id="r_s">
        <div class="bg-`+status+` text-white py-3 px-3 px-md-3">
            <i class="fas fa-`+icon+` h5 mb-0 ">
        </i></div>
        <div class="px-2 px-md-3 py-3 d-flex align-self-center justify-content-between w-100">
            <span>`+msg+`</span>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>`);
    eval(evalx);
    eval(a);
}
var scheme = function(){
    if (Cookies.get('scheme') !== undefined) { 
        Cookies.remove('scheme');
        $("LINK[href*='assets/dark.css']").remove();
        $('#icon_mode').removeClass('fa-cloud-sun');
        $('#icon_mode').addClass('fa-cloud-moon');
    } else {     
        Cookies.set('scheme', 'dark');
        $('head').append('<link rel="stylesheet" href="assets/dark.css" type="text/css" />');
        $('#icon_mode').removeClass('fa-cloud-moon');
        $('#icon_mode').addClass('fa-cloud-sun');
    }
}

function fp( )
{
    $.getJSON( "a.php?a=point_fetch", [], function(data) {
        old_point=parseFloat($('#user_point').text());
        point=parseFloat(data.point);
        if(point>old_point){
            swal('success', 'พ้อยเพิ่มแล้ว', 'check');
        }
        $('#user_point').text(data.point);
    });
}
