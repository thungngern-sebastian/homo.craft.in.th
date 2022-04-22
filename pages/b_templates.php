<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $template=[];
    $query = _que('SELECT * FROM hosts');
    if(!is_array($query) || @!isset($query['failed'])){
        $hosts = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($hosts)){
            require_once('../API/Xen.php');
            foreach ($hosts as $host) {
                $xen = new PsXenAPI($host['host'],$host['username'],$host['password']);
                if(is_null($xen->id_session)){ continue; }
                $templates=$xen->rq('VM.get_all_records_where', ['field "is_a_template" = "true" and field "is_default_template" = "false" and field "is_a_snapshot" = "false" and field "is_control_domain" = "false"']);
                if($templates['Status']!='Success'){ continue; }
                foreach ($templates['Value'] as $template_key=>$template_data) {
                    $template[$host['host']][$xen::cutref($template_key)]=$template_data;
                }
            }
        }
    }
    $os=[];
    $query = _que('SELECT ref,os.* FROM os');
    if(!is_array($query) || @!isset($query['failed'])){
        $os = $query->fetchAll(PDO::FETCH_UNIQUE);
    }
?>

<div class="container pb-5">
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar">
        <h3>Template</h3>
        </div>
        <table 
        class="table"
        id="table"
        data-toggle="table"
        data-toolbar=".toolbar"
        data-sortable="true"
        data-show-columns="true"
        data-pagination="true"
        data-show-columns-toggle-all="true"
        data-search="true">
        <thead>
            <tr>
                <th data-sortable="true">Name</th>
                <th data-sortable="true">Ref</th>
                <th data-sortable="true">host</th>
                <th data-sortable="true">Is in DB</th>
                <th data-sortable="true">OS DB</th>
                <th data-sortable="true">Distro DB</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($template as $host => $host_template) {
                    foreach ($host_template as $ref => $data) {
                        //if(strpos(strtolower($data['name_label']), 'Windows') === false){ continue;}
                        $is_in_db = (!empty($os[$ref]));
                        $oss= ($is_in_db)?$os[$ref]:[];
                        if($is_in_db){
                            unset($os[$ref]);
                        }
                        ?>
                            <tr>
                                <th scope="row"><?= str_replace('Template','',$data['name_label'])?></th>
                                <td><small><?= $ref?><small></td>
                                <td><small><?= $host?><small></td>
                                <td><?= ($is_in_db)?'✔':'❌'?></td>
                                <td><small><?= @$oss['os']?><small></td>
                                <td><small><?= @$oss['distro']?><small></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_template" data-host="<?= $host?>" data-ref="<?= $ref?>" data-os="<?= @$oss['os']?>" data-distro="<?= @$oss['distro']?>"><i class="fal fa-<?= ($is_in_db)?'edit':'plus'?>"></i></button>
                                    <?= (($is_in_db)?'<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_template" data-host="'.$host.'" data-ref="'.$ref.'"><i class="fal fa-trash"></i></button></td>':'')?>
                                </td>
                            </tr>
                        <?php
                    }
                }
                foreach ($os as $ref => $data) {
                    ?>
                        <tr class="text-muted">
                            <th scope="row">This template doesn't in XCP-NG</th>
                            <td><small><?= $ref?><small></td>
                            <td><small><?= $data['host']?><small></td>
                            <td>✔</td>
                            <td><small><?= $data['os']?><small></td>
                            <td><small><?= $data['distro']?><small></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_template" data-host="<?=$data['host']?>" data-ref="<?=$ref?>"><i class="fal fa-trash"></i></button></td>
                            </td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
        </table>
        
    </div>
</div>

<div class="modal fade" id="add_template" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_template_update",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control form-control-sm" name="host" readonly>
            </div>
            <div class="form-group">
                <label>REF</label>
                <input type="text" class="form-control form-control-sm" name="ref" readonly>
            </div>
            <div class="form-group">
                <label>OS (Eg. Windows Server)</label>
                <input type="text" class="form-control" name="os">
            </div>
            <div class="form-group">
                <label>Distro (Eg. 2012 ( Windows Server 2012 ))</label>
                <input type="text" class="form-control" name="distro">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add/Edit Template</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="remove_template" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Remove template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_template_remove",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control form-control-sm" name="host" readonly>
            </div>
            <div class="form-group">
                <label>REF</label>
                <input type="text" class="form-control form-control-sm" name="ref" readonly>
            </div>
            <p>* This only remove from database</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Remove Template</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $('#add_template').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var host = button.data('host')
        var ref = button.data('ref')
        var os = button.data('os')
        var distro = button.data('distro')
        var modal = $(this)
        $('input[name$="host"]').val(host)
        $('input[name$="ref"]').val(ref)
        $('input[name$="os"]').val(os)
        $('input[name$="distro"]').val(distro)
    });
    $('#remove_template').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var host = button.data('host')
        var ref = button.data('ref')
        var modal = $(this)
        $('input[name$="host"]').val(host)
        $('input[name$="ref"]').val(ref)
    })
</script>
</div>