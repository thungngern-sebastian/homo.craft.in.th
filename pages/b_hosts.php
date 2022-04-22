<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $hosts=[];
    $query = _que('SELECT * FROM hosts');
    if(!is_array($query) || @!isset($query['failed'])){
        $hosts = $query->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<div class="container pb-5">
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar d-flex flex-row">
        <h3 class="mb-0">Hosts</h3>
        <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#add_host" data-host="" data-user="" data-pass="" data-public="" data-manitacne=""><i class="fal fa-plus"></i></button>
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
                <th data-sortable="true">Host</th>
                <th data-sortable="true">User</th>
                <th data-sortable="true">Password</th>
                <th data-sortable="true">Is public?</th>
                <th data-sortable="true">Is manitance?</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($hosts as $value) {
                ?>
                <tr>
                    <th><?= $value['host']?></th>
                    <th><?= $value['username']?></th>
                    <th><?= $value['password']?></th>
                    <th><?= ($value['public']==1)?'✔':'❌'?></th>
                    <th><?= ($value['manitance']==1)?'✔':'❌'?></th>
                    <th>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_host" data-host="<?= $value['host']?>" data-user="<?= $value['username']?>" data-pass="<?= $value['password']?>" data-public="<?= $value['public']?>" data-manitance="<?= $value['manitance']?>"><i class="fal fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_host" data-host="<?= $value['host']?>"><i class="fal fa-trash"></i></button></td>
                    </th>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
        
    </div>
</div>
<div class="modal fade" id="add_host" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit Host</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_host_update",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control" name="host">
            </div>
            <div class="form-group">
                <label>User</label>
                <input type="text" class="form-control" name="user">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="text" class="form-control" name="pass">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="public" value="1">
                <label class="form-check-label">Is Public?</label>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="manitance" value="1">
                <label class="form-check-label">Is Manite?</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add/Edit Host</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="remove_host" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Remove Host</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_host_remove",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>Host</label>
                <input type="text" class="form-control form-control-sm" name="host" readonly>
            </div>
            <p>* This only remove from database</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Remove Host</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $('#add_host').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var host = button.data('host')
        var user = button.data('user')
        var pass = button.data('pass')
        var public = button.data('public')
        var manitance = button.data('manitance')
        var modal = $(this)
        $('input[name$="host"]').val(host)
        $('input[name$="user"]').val(user)
        $('input[name$="pass"]').val(pass)
        if(public==1){
            $('input[name$="public"]'). prop("checked", true);
        } else {
            $('input[name$="public"]'). prop("checked", false);
        }
        if(manitance==1){
            $('input[name$="manitance"]'). prop("checked", true);
        } else {
            $('input[name$="manitance"]'). prop("checked", false);
        }
    });
    $('#remove_host').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var host = button.data('host')
        var modal = $(this)
        $('input[name$="host"]').val(host)
    })
</script>
</div>