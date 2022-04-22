<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $setting=[];
    $query = _que('SELECT * FROM setting');
    if(!is_array($query) || @!isset($query['failed'])){
        $setting = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    $color = json_decode($setting[0]['color']);
    $stmp = json_decode($setting[0]['stmp']);
?>
    <div class="container pb-5">
        <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <h3><i class="fas fa-cog mr-2"></i>Setting</h3>
                    <hr>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="logo-bg-tab" data-toggle="tab" href="#logo-bg" role="tab" aria-controls="logo-bg" aria-selected="true">Logo & Background</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Color</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="STMP-tab" data-toggle="tab" href="#STMP" role="tab" aria-controls="STMP" aria-selected="false">Mail Setting</a>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="Database-tab" data-toggle="tab" href="#Database" role="tab" aria-controls="Database" aria-selected="false">Database Setting</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="ts-tab" data-toggle="tab" href="#ts" role="tab" aria-controls="ts" aria-selected="false">Translate</a>
                    </li> -->
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade mt-3 show active" id="logo-bg" role="tabpanel" aria-labelledby="logo-bg-tab">
                        <form onSubmit='ajax("b_setting_update",$(this).serialize());return false;'>
                            <div class="form-group">
                                <label>Icon</label>
                                <!-- <input type="file" name="icon" accept="image/*" class="form-control-file"> -->
                                <input type="text" class="form-control" name="icon" value="<?php echo $setting[0]['icon'];?>">
                            </div>
                            <center>
                                <img src="<?php echo $setting[0]['icon'];?>" alt="" width="300px">
                            </center>
                            <div class="form-group">
                                <label>Logo</label>
                                <!-- <input type="file" id='logo' name="logo" accept="image/*" class="form-control-file"> -->
                                <input type="text" class="form-control" name="logo" value="<?php echo $setting[0]['logo'];?>">
                            </div>
                            <center>
                                <img src="<?php echo $setting[0]['logo'];?>" alt="" width="300px">
                            </center>
                            <div class="form-group">
                                <label>Logo Footer</label>
                                <!-- <input type="file" id='logo' name="logo" accept="image/*" class="form-control-file"> -->
                                <input type="text" class="form-control" name="logo_footer" value="<?php echo $setting[0]['logo_footer'];?>">
                            </div>
                            <center>
                                <img src="<?php echo $setting[0]['logo_footer'];?>" alt="" width="300px">
                            </center>
                            <div class="form-group">
                                <label>Background</label>
                                <!-- <input type="file" name="background" accept="image/*" class="form-control-file"> -->
                                <input type="text" class="form-control" name="bg" value="<?php echo $setting[0]['bg'];?>">
                            </div>
                            <center>
                                <img src="<?php echo $setting[0]['bg'];?>" alt="" width="300px">
                            </center>
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div>
                    <div class="tab-pane fade mt-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form onSubmit='ajax("b_setting_color",$(this).serialize());return true;'>
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
                    </div>
                    <div class="tab-pane fade mt-3" id="contact" role="tabpanel" aria-labelledby="contact-tab">
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
                    </div>
                    <div class="tab-pane fade mt-3" id="STMP" role="tabpanel" aria-labelledby="STMP-tab">
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
                    </div>
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