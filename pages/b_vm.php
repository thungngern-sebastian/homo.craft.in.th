<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    ?>

<div class="container pb-5">
    <?php
    if(empty($_GET['ref']) || empty($_GET['h'])){
        $vm=[];
        $query = _que('SELECT * FROM hosts WHERE manitance=0');
        if(!is_array($query) || @!isset($query['failed'])){
            $hosts = $query->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($hosts)){
                require_once('../API/Xen.php');
                foreach ($hosts as $host) {
                    $xen = new PsXenAPI($host['host'],$host['username'],$host['password']);
                    if(is_null($xen->id_session)){ continue; }
                    $templates=$xen->rq('VM.get_all_records_where', ['field "is_a_template" = "false" and field "is_default_template" = "false" and field "is_a_snapshot" = "false" and field "is_control_domain" = "false"']);
                    if($templates['Status']!='Success'){ continue; }
                    foreach ($templates['Value'] as $template_key=>$template_data) {
                        $vm[$host['host']][$xen::cutref($template_key)]=$template_data;
                    }
                }
            }
        }
        $vmx=[];
        $query = _que('SELECT ref,vm.* FROM vm');
        if(!is_array($query) || @!isset($query['failed'])){
            $vmx = $query->fetchAll(PDO::FETCH_UNIQUE);
        }
    ?>
    
    <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
	
	<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
	<script src="https://unpkg.com/bootstrap-table@1.17.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <div class="card">
        <div class="card-body">
            <div class="toolbar">
            <h3>VM</h3>
            </div>
            <table 
            class="table table-sm"
            id="table"
            data-toggle="table"
            data-toolbar=".toolbar"
            data-sortable="true"
            data-show-columns="true"
            data-pagination="true"
            data-show-columns-toggle-all="true"
			data-show-export="true"
			data-page-list="[10, 25, 50, 100, all]"
            data-search="true">
            <thead>
                <tr>
                    <th data-sortable="true">Name</th>
                    <th data-sortable="true">Ref</th>
                    <th data-sortable="true">host</th>
                    <th data-sortable="true">in DB?</th>
                    <th data-sortable="true">Cus id</th>
                    <th data-sortable="true">Timestamp</th>
                    <th data-sortable="true">Is expired?</th>
                    <th data-sortable="true">Pause?</th>
                    <th data-sortable="true">Unlimit?</th>
                    <th data-sortable="true">OS</th>
                    <th data-sortable="true">Distro</th>
                    <th data-sortable="true">Plan</th>
                    <th data-sortable="true">PPD</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($vm as $key => $value) {
                    foreach ($value as $keyx => $valuex) {
                        $is_in_db = (!empty($vmx[$keyx]));
                        $vmm= ($is_in_db)?$vmx[$keyx]:[];
                        $exx = "n";
                        if($is_in_db){
                            unset($vmx[$keyx]);
                            if(strtotime($vmm['timestamp']) < strtotime("now") &&$vmm['unlimited']!=1){
                                $tmp_hr = floor((strtotime("now") - strtotime($vmm['timestamp']))/ ( 60 * 60 ));
                                $exx="expired for {$tmp_hr} HR";
                            }
                        }
                        ?>
                        <tr>
                            <td><small><?= $valuex['name_label']?><br><?= $vmm['user_label']?></small></td>
                            <td><small><?= $keyx?></small></td>
                            <td><small><?= $key?></small></td>
                            <td><?= ($is_in_db)?'✔':'❌'?></td>
                            <td><?= $vmm['cusid']?></td>
                            <td><?= $vmm['timestamp']?></td>
                            <td><?= $exx?></td>
                            <td><?= ($vmm['pause']==1)?'✔':'❌'?></td>
                            <td><?= ($vmm['unlimited']==1)?'✔':'❌'?></td>
                            <td><?= $vmm['os']?></td>
                            <td><?= $vmm['distro']?></td>
                            <td><?= $vmm['plan']?>/<?= $vmm['cpu']?>C/<?= $vmm['ram']?>G/<?= $vmm['disk']?>G</td>
                            <td><?= $vmm['base_price']?></td>
                            <td><a class="btn btn-primary" href="?page=b_vm&ref=<?= $keyx?>&h=<?= $key?>">S</a></td>
                        </tr>
        
                        <?php
                    }
                }
                ?>
            </tbody>
            </table>
            
        </div>
    </div>
<?php    
    } else {
        $query = _que('SELECT * FROM hosts WHERE host=?',[$_GET['h']]);
        if(!is_array($query) || @!isset($query['failed'])){
            $host = $query->fetch(PDO::FETCH_ASSOC);
            if(!empty($host)){
                require_once('../API/Xen.php');
                $xen = new PsXenAPI($host['host'],$host['username'],$host['password']);
                if(!is_null($xen->id_session)){
                    $templates=$xen->rq('VM.get_record', [$xen::apref($_GET['ref'])]);
                    if($templates['Status']=='Success'){
                        $vm=$templates['Value'];
                    }
                }
            }
        }
        $vmx=[];
        $query = _que('SELECT * FROM vm 
        INNER JOIN customer ON vm.cusid = customer.id WHERE ref = ?',[$_GET['ref']]);
        if(!is_array($query) || @!isset($query['failed'])){
            $vmx = $query->fetch(PDO::FETCH_ASSOC);
        }
        $plans=[];
        $query = _que('SELECT * FROM plans',[]);
        if(!is_array($query) || @!isset($query['failed'])){
            $plans = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>
    <div class="card">
        <div class="card-body">
            <h4><?= (!empty($vm))?$vm['name_label']:'No vm in server'?></h4>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <form onSubmit='ajax("b_vm_update",$(this).serialize());return false;'>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ref</label>
                                <input type="text" class="form-control form-control-sm" name="ref" value="<?=$_GET['ref']?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>host</label>
                                <input type="text" class="form-control form-control-sm" name="host" value='<?= (!empty($vmx['host']))?$vmx['host']:$_GET['h']?>'>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>User label</label>
                                <input type="text" class="form-control" name="user_label" value='<?=$vmx['user_label']?>'>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>cusid</label>
                                <input type="text" class="form-control" name="cusid" value='<?=$vmx['cusid']?>'>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>timestamp</label>
                                <input type="text" class="form-control" name="timestamp" value='<?=$vmx['timestamp']?>'>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>template</label>
                                <input type="text" class="form-control" name="template" value='<?=$vmx['template']?>'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Plane</label>
                                    <select class="form-control" name="plan">
                                    <option value="Custom">Custom</option>
                                    <?php
                                    foreach ($plans as $p) {
                                        echo'<option value="'.$p['plan'].'"'.(($vmx['plan']==$p['plan'])?' Selected':'')." onClick='auto_spec({$p['cpu']},{$p['ram']},{$p['disk']},{$p['price']})'>{$p['plan']}</option>";
                                    }
                                    ?>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>os</label>
                                <input type="text" class="form-control" name="os" value='<?=$vmx['os']?>'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>distro</label>
                                <input type="text" class="form-control" name="distro" value='<?=$vmx['distro']?>'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>User password</label>
                                <input type="text" class="form-control" name="user_password" value='<?=$vmx['user_password']?>'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>lenght</label>
                                <input type="number" class="form-control" name="lenght" value='<?=$vmx['lenght']?>'>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cpu</label>
                                <input type="number" class="form-control" name="cpu" min=1 value='<?=$vmx['cpu']?>' onInput="custom()">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Ram(GB)</label>
                                <input type="number" class="form-control" name="ram" min=1 value='<?=$vmx['ram']?>' onInput="custom()">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Disk (GB)</label>
                                <input type="number" class="form-control" name="disk" value='<?=$vmx['disk']?>' onInput="custom()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>base price</label>
                                <input type="number" class="form-control" name="base_price" value='<?=$vmx['base_price']?>' onInput="custom()">
                            </div>
                        </div>
                    </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="pause" value="1" <?= ($vmx['pause']==1)?'checked':''?>>
                            <label class="form-check-label">Is Expired</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="unlimited" value="1" <?= ($vmx['unlimited']==1)?'checked':''?>>
                            <label class="form-check-label">Is Unlimited</label>
                        </div>
                        <p>* Save this setting before using something on right size or bottom</p>
                        <a href="?page=b_vm" class="btn btn-secondary" data-dismiss="modal">I want to go home</a>
                        <button type="submit" class="btn btn-primary">Add/Edit VM</button>
                        <?php if(!empty($vmx)){ ?>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#remove_vm">Remove VM</button>
                        <?php } ?>
                    </form>
                </div>
                <div class="col col-md-4">
                    <?php
                        if(!empty($vm)){ ?>
                        
              <button class="btn btn-danger btn-block" onclick="ajax('b_controller',{'ref':$('input[name$=\'ref\']').val(),'host':$('input[name$=\'host\']').val(),'action':'shutdown'})">Shutdown</button>
              <button class="btn btn-danger btn-block" onclick="ajax('b_controller',{'ref':$('input[name$=\'ref\']').val(),'host':$('input[name$=\'host\']').val(),'action':'restart'})">Restart</button>
              <button class="btn btn-danger btn-block" onclick="ajax('b_controller',{'ref':$('input[name$=\'ref\']').val(),'host':$('input[name$=\'host\']').val(),'action':'forceshutdown'})">Force Shutdown</button>
              <button class="btn btn-danger btn-block" onclick="ajax('b_controller',{'ref':$('input[name$=\'ref\']').val(),'host':$('input[name$=\'host\']').val(),'action':'forcerestart'})">Force Restart</button>
              <button class="btn btn-success btn-block" onclick="ajax('b_controller',{'ref':$('input[name$=\'ref\']').val(),'host':$('input[name$=\'host\']').val(),'action':'start'})">Start</button>
              <hr>
                <?php if(!empty($vmx)){ ?>
              <button class="btn btn-primary btn-block" onclick="ajax('b_vm_info',{'ref':$('input[name$=\'ref\']').val()})">Update VM title</button>
              <button class="btn btn-primary btn-block" onclick="ajax('b_vm_spec',{'ref':$('input[name$=\'ref\']').val()})">Update VM Spec (Shutdown)</button>
                <?php } ?>
              <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#change_ip">Change IP (Shutdown)</button>
              <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete_vm">Delete VM</button>

                    <?php    }
                    ?>
                </div>
            </div>
           
        </div>
    </div>
    <?php
        if(!empty($vmx)){ ?>
            <div class="modal fade" id="remove_vm" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove VM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form onSubmit='ajax("b_vm_remove",$(this).serialize());return false;'>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" class="form-control form-control-sm"  id="x_host" readonly>
                            </div>
                            <div class="form-group">
                                <label>REF</label>
                                <input type="text" class="form-control form-control-sm" name="xref" readonly>
                            </div>
                            <p>* This only remove from database AND ip_address will br</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Remove VM</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
    <?php    }
    ?>
    <?php
        if(!empty($vm)){ ?>
            <div class="modal fade" id="delete_vm" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete VM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form onSubmit='ajax("b_vm_delete",$(this).serialize());return false;'>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" class="form-control form-control-sm" name="chost" readonly>
                            </div>
                            <div class="form-group">
                                <label>REF</label>
                                <input type="text" class="form-control form-control-sm" name="cref" readonly>
                            </div>
                            <p>* This only remove from XEN SERVER</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete VM</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="change_ip" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change IP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form onSubmit='ajax("b_vm_ip",$(this).serialize());return false;'>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" class="form-control form-control-sm" name="zhost" readonly>
                            </div>
                            <div class="form-group">
                                <label>REF</label>
                                <input type="text" class="form-control form-control-sm" name="zref" readonly>
                            </div>
                            <div class="form-group">
                                <label>IP</label>
                                <input type="text" class="form-control form-control-sm" name="ip">
                            </div>
                            <p>* This edit IP in database and VM on Xen server</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Change IP</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
    <?php    }
    ?>
    <script>
        $('#remove_vm').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var host = $('input[name$="host"]').val()
            var ref = $('input[name$="ref"]').val()
            var modal = $(this)
            $('#x_host').val(host)
            $('input[name$="xref"]').val(ref)
        })
        $('#delete_vm').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var host = $('input[name$="host"]').val()
            var ref = $('input[name$="ref"]').val()
            var modal = $(this)
            $('input[name$="chost"]').val(host)
            $('input[name$="cref"]').val(ref)
        })
        $('#change_ip').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var host = $('input[name$="host"]').val()
            var ref = $('input[name$="ref"]').val()
            var modal = $(this)
            $('input[name$="zhost"]').val(host)
            $('input[name$="zref"]').val(ref)
        })

        function custom(){
            $('select[name$="plan"]').val('Custom')
        }
        function auto_spec(c,r,d,p){
            $('input[name$="cpu"]').val(c)
            $('input[name$="ram"]').val(r)
            $('input[name$="disk"]').val(d)
            $('input[name$="base_price"]').val(p)
        }
    </script>
        <?php
    }
    ?>
    </div>