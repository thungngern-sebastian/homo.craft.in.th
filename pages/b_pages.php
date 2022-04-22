<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $pages=[];
    $query = _que('SELECT * FROM pages WHERE `page`="home"');
    if(!is_array($query) || @!isset($query['failed'])){
      $pages = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    $json = json_decode($pages[0]['json']);
    $zone1 = $json->zone1;
    $zone1 = json_decode($json->zone1);
    $zone2 = json_decode($json->zone2);
    $plan1 = json_decode($json->plan1);
    $plan2 = json_decode($json->plan2);
    $plan3 = json_decode($json->plan3);
    
?>
    <div class="container pb-5">
        <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <h3><i class="fas fa-window-maximize mr-2"></i>Pages</h3>
                    <hr>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="logo-bg-tab" data-toggle="tab" href="#logo-bg" role="tab" aria-controls="logo-bg" aria-selected="true">Home Page</a>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Color</a>
                    </li> -->
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                    </li> -->
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="STMP-tab" data-toggle="tab" href="#STMP" role="tab" aria-controls="STMP" aria-selected="false">Mail Setting</a>
                    </li> -->
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="Database-tab" data-toggle="tab" href="#Database" role="tab" aria-controls="Database" aria-selected="false">Database Setting</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="ts-tab" data-toggle="tab" href="#ts" role="tab" aria-controls="ts" aria-selected="false">Translate</a>
                    </li> -->
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade mt-3 show active" id="logo-bg" role="tabpanel" aria-labelledby="logo-bg-tab">
                        <form onSubmit='ajax("b_pages",$(this).serialize());return false;'>
                            <div class="container text-lg-left text-md-left text-sm-left text-xs-left text-center">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="section-title pb-10">
                                            <h4 class="title"><input type="text" name="title" class="form-control" value="<?php echo $zone1->title; ?>"></h4>
                                            <p class="text"><input type="text" name="desc" class="form-control" value="<?php echo $zone1->desc; ?>"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="services-content mt-40 d-sm-flex">
                                                    <div class="services-icon">
                                                        <i class="fal fa-tachometer-alt"></i>
                                                    </div>
                                                    <div class="services-content media-body">
                                                        <h4 class="services-title"><input type="text" name="i1_title" class="form-control" value="<?php echo $zone1->i1_title; ?>"></h4>
                                                        <p class="text"><input type="text" name="i1_desc1" class="form-control" value="<?php echo $zone1->i1_desc1; ?>">
                                                        <input type="text" class="form-control" name="i1_desc2" value="<?php echo $zone1->i1_desc2; ?>"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="services-content mt-40 d-sm-flex">
                                                    <div class="services-icon">
                                                        <i class="fal fa-wand-magic"></i>
                                                    </div>
                                                    <div class="services-content media-body">
                                                        <h4 class="services-title"><input type="text" name="i2_title" class="form-control" value="<?php echo $zone1->i2_title; ?>"></h4>
                                                        <p class="text"><input type="text" name="i2_desc1" class="form-control" value="<?php echo $zone1->i2_desc1; ?>">
                                                        <input type="text" name="i2_desc1" class="form-control" value="<?php echo $zone1->i2_desc1; ?>"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="services-content mt-40 d-sm-flex">
                                                    <div class="services-icon">
                                                        <i class="fal fa-money-bill"></i>
                                                    </div>
                                                    <div class="services-content media-body">
                                                        <h4 class="services-title"><input type="text" name="i3_title" class="form-control" value="<?php echo $zone1->i3_title; ?>"></h4>
                                                        <p class="text"><input type="text" name="i3_desc1" class="form-control" value="<?php echo $zone1->i3_desc1; ?>">
                                                        <input type="text" name="i3_desc2" class="form-control" value="<?php echo $zone1->i3_desc2; ?>"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="services-content mt-40 d-sm-flex">
                                                    <div class="services-icon">
                                                        <i class="fal fa-wallet"></i>
                                                    </div>
                                                    <div class="services-content media-body">
                                                        <h4 class="services-title"><input type="text" name="i4_title" class="form-control" value="<?php echo $zone1->i4_title; ?>"></h4>
                                                        <p class="text"><input type="text" name="i4_desc1" class="form-control" value="<?php echo $zone1->i4_desc1; ?>">
                                                        <input type="text" name="i4_desc2" class="form-control" value="<?php echo $zone1->i4_desc2; ?>"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="type" value="zone1">
                            <input type="hidden" name="page" value="home">
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                            <hr>
                        </form>
                            <!--<div class="services-image d-lg-flex align-items-center">
                                <div class="image"><img alt="Services" src="assets/services.png"></div>
                            </div>-->
                        <section class="pricing-area" id="pricedrite">
                            <div class="container">
                                <form onSubmit='ajax("b_pages",$(this).serialize());return false;'>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6">
                                            <div class="section-title text-center pb-5">
                                                <h4 class="title"><input type="text" name="title" class="form-control" value="<?php echo $zone2->title; ?>"></h4>
                                                <p class="text"><input type="text" name="desc1" class="form-control" value="<?php echo $zone2->desc1; ?>">
                                                <input type="text" class="form-control" name="desc2" value="<?php echo $zone2->desc2; ?>"></p>
                                                <input type="hidden" name="type" value="zone2">
                                                <input type="hidden" name="page" value="home">
                                                <button type="submit" class="btn btn-primary btn-block">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="row justify-content-center">
                                    <div class="col-lg-4 col-md-7 col-sm-9">
                                        <form onSubmit='ajax("b_pages",$(this).serialize());return false;'>
                                            <div class="single-pricing pro mt-40">
                                                <div class="pricing-header">
                                                    <h5 class="sub-title"><input type="text" name="title" class="form-control" value="<?php echo $plan1->title; ?>"></h5>
                                                    <span class="price"><input type="text" name="price" class="form-control" value="<?php echo $plan1->price; ?>"></span>
                                                    <p class="basic-pkg"><input type="text" name="note" class="form-control" value="<?php echo $plan1->note; ?>"></p>
                                                </div>
                                                <div class="pricing-list">
                                                    <ul>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-microchip"></i> <input type="text" name="cpu" class="form-control" value="<?php echo $plan1->cpu; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-memory"></i> <input type="text" name="ram" class="form-control" value="<?php echo $plan1->ram; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-hdd"></i> <input type="text" name="disk" class="form-control" value="<?php echo $plan1->disk; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-headset"></i> <input type="text" name="support" class="form-control" value="<?php echo $plan1->support; ?>"></li>
                                                    </ul>
                                                </div>
                                                <div class="pricing-btn text-center">
                                                    <input type="hidden" name="type" value="plan1">
                                                    <input type="hidden" name="page" value="home">
                                                    <button type="submit" class="main-btn"><i class="fas fa-pencil-alt"></i> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-4 col-md-7 col-sm-9">
                                        <form onSubmit='ajax("b_pages",$(this).serialize());return false;'>
                                            <div class="single-pricing pro mt-40">
                                                <div class="pricing-header">
                                                    <h5 class="sub-title"><input type="text" name="title" class="form-control" value="<?php echo $plan2->title; ?>"></h5>
                                                    <span class="price"><input type="text" name="price" class="form-control" value="<?php echo $plan2->price; ?>"></span>
                                                    <p class="basic-pkg"><input type="text" name="note" class="form-control" value="<?php echo $plan2->note; ?>"></p>
                                                </div>
                                                <div class="pricing-list">
                                                    <ul>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-microchip"></i> <input type="text" name="cpu" class="form-control" value="<?php echo $plan2->cpu; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-memory"></i> <input type="text" name="ram" class="form-control" value="<?php echo $plan2->ram; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-hdd"></i> <input type="text" name="disk" class="form-control" value="<?php echo $plan2->disk; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-headset"></i> <input type="text" name="support" class="form-control" value="<?php echo $plan2->support; ?>"></li>
                                                    </ul>
                                                </div>
                                                <div class="pricing-btn text-center">
                                                    <input type="hidden" name="type" value="plan2">
                                                    <input type="hidden" name="page" value="home">
                                                    <button type="submit" class="main-btn"><i class="fas fa-pencil-alt"></i> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-4 col-md-7 col-sm-9">
                                        <form onSubmit='ajax("b_pages",$(this).serialize());return false;'>
                                            <div class="single-pricing pro mt-40">
                                                <div class="pricing-header">
                                                    <h5 class="sub-title"><input type="text" name="title" class="form-control" value="<?php echo $plan3->title; ?>"></h5>
                                                    <span class="price"><input type="text" name="price" class="form-control" value="<?php echo $plan3->price; ?>"></span>
                                                    <p class="basic-pkg"><input type="text" name="note" class="form-control" value="<?php echo $plan3->note; ?>"></p>
                                                </div>
                                                <div class="pricing-list">
                                                    <ul>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-wifi"></i> <input type="text" class="form-control" name="net" value="<?php echo $plan3->net; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-location-arrow"></i> <input type="text" name="ip" class="form-control" value="<?php echo $plan3->ip; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-plug"></i> <input type="text" name="power" class="form-control" value="<?php echo $plan3->power; ?>"></li>
                                                        <li><i class="lni-check-mark-circle"></i> <i class="fal fa-headset"></i> <input type="text" name="support" class="form-control" value="<?php echo $plan3->support; ?>"></li>
                                                    </ul>
                                                </div>
                                                <div class="pricing-btn text-center">
                                                    <input type="hidden" name="type" value="plan3">
                                                    <input type="hidden" name="page" value="home">
                                                    <button type="submit" class="main-btn"><i class="fas fa-pencil-alt"></i> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- <div class="tab-pane fade mt-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form onSubmit='ajax("b_setting_color",$(this).serialize());return false;'>
                            <div class="form-group" id="colorizer">
                                <label>Background Color</label>
                                <input id="bg-color" class="form-control" type="text" name="bg-color" value="<?php echo $color->bg;?>" style="background:<?php echo $color->bg;?>;">
                            </div>
                            <div class="form-group" id="colorizer">
                                <label>Primary Color</label>
                                <input id="primary-color" class="form-control" type="text" name="primary" value="<?php echo $color->primary;?>" style="background:<?php echo $color->primary;?>;">
                            </div>
                            <div class="form-group" id="colorizer">
                                <label>Primary Hover</label>
                                <input id="primary_hover-color" class="form-control" type="text" name="primary_hover" value="<?php echo $color->primary_hover;?>" style="background:<?php echo $color->primary_hover;?>;">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div> -->
                    <!-- <div class="tab-pane fade mt-3" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <form onSubmit='ajax("b_setting_contact",$(this).serialize());return false;'>
                            <div class="form-group">
                                <label>ที่อยู่</label>
                                <input type="text" class="form-control" name="address_th" value="<?php echo $setting[0]['address_th'];?>">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" name="address_en" value="<?php echo $setting[0]['address_en'];?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div> -->
                    <!-- <div class="tab-pane fade mt-3" id="STMP" role="tabpanel" aria-labelledby="STMP-tab">
                        <form onSubmit='ajax("b_setting_stmp",$(this).serialize());return false;'>
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" class="form-control" name="host" value="<?php echo $stmp->host;?>">
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="user" value="<?php echo $stmp->username;?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="pass" value="<?php echo $stmp->password;?>">
                            </div>
                            <div class="form-group">
                                <label>Mail</label>
                                <input type="text" class="form-control" name="mail" value="<?php echo $stmp->mail;?>">
                            </div>
                            <div class="form-group">
                                <label>Mail Title</label>
                                <input type="text" class="form-control" name="mail_title" value="<?php echo $stmp->title;?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div> -->
                    <!-- <div class="tab-pane fade mt-3" id="Database" role="tabpanel" aria-labelledby="Database-tab">
                        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
                        <link rel="stylesheet" href="assets/editormd.min.css" />
                        <script src="assets/editormd.min.js"></script>
                        <script src="assets/plugins/en.js"></script>
                        <form onSubmit='ajax("b_setting_setting",$(this).serialize());return false;'>
                            <?php
                                $file_path='../setting.md';
                                $data = file_get_contents($file_path);
                                print_r(json_decode($data));
                                echo $data;
                            ?>
                            <div id="editor">
                                <textarea style="display:none;" name="editor-markdown-doc"><?php echo $data;?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div>
                    <div class="tab-pane fade mt-3" id="ts" role="tabpanel" aria-labelledby="ts-tab">
                        <form onSubmit='ajax("b_setting_stmp",$(this).serialize());return false;'>
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" class="form-control" name="host" value="<?php echo $stmp->host;?>">
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="user" value="<?php echo $stmp->username;?>">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="pass" value="<?php echo $stmp->password;?>">
                            </div>
                            <div class="form-group">
                                <label>Mail</label>
                                <input type="text" class="form-control" name="mail" value="<?php echo $stmp->mail;?>">
                            </div>
                            <div class="form-group">
                                <label>Mail Title</label>
                                <input type="text" class="form-control" name="mail_title" value="<?php echo $stmp->title;?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(function() {
        var editor = editormd("editor", {
            path   : "assets/editor_md_lib/",
            width  : "100%",
            height : "700px",
            htmlDecode : true
        });
    });
    document.getElementById( "bg-color" ).addEventListener( "focus", function (e) {
        /* { "id" : null, "container" : "the container for widget(required)", "value" : "rgba(1,1,1,1)(required)" } */
        var data = {
            "id" : null,
            "container" : document.getElementById("colorizer"),
            "value" : document.getElementById("bg-color").value
        }
        var colorizer = new Gn8Colorize( data );
        colorizer.init().then( 
            success => {
                /* { "hex" : "#ff0000", "rgb" : "rgba(255,0,0,1)", "name" : "red", "theme" : "dark | light" } */
                document.getElementById("bg-color").value = success.hex;
                document.getElementById("bg-color").style.background = success.hex;
                console.log( success );
            }, error => {
                console.log( error );
            } 
        );
    } );
    document.getElementById( "primary-color" ).addEventListener( "focus", function (e) {
        /* { "id" : null, "container" : "the container for widget(required)", "value" : "rgba(1,1,1,1)(required)" } */
        var data = {
            "id" : null,
            "container" : document.getElementById("colorizer"),
            "value" : document.getElementById("primary-color").value
        }
        var colorizer = new Gn8Colorize( data );
        colorizer.init().then( 
            success => {
                /* { "hex" : "#ff0000", "rgb" : "rgba(255,0,0,1)", "name" : "red", "theme" : "dark | light" } */
                document.getElementById("primary-color").value = success.hex;
                document.getElementById("primary-color").style.background = success.hex;
                console.log( success );
            }, error => {
                console.log( error );
            } 
        );
    } );
    document.getElementById( "primary_hover-color" ).addEventListener( "focus", function (e) {
        /* { "id" : null, "container" : "the container for widget(required)", "value" : "rgba(1,1,1,1)(required)" } */
        var data = {
            "id" : null,
            "container" : document.getElementById("colorizer"),
            "value" : document.getElementById("primary_hover-color").value
        }
        var colorizer = new Gn8Colorize( data );
        colorizer.init().then( 
            success => {
                /* { "hex" : "#ff0000", "rgb" : "rgba(255,0,0,1)", "name" : "red", "theme" : "dark | light" } */
                document.getElementById("primary_hover-color").value = success.hex;
                document.getElementById("primary_hover-color").style.background = success.hex;
                console.log( success );
            }, error => {
                console.log( error );
            } 
        );
    } );
</script>