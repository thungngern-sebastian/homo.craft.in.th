<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $hosts=[];
    $query = _que('SELECT id,fname,lname,email,id_card,phone,fbid,point,is_activated,suspended FROM customer');
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
        <h3 class="mb-0">Customers</h3>
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
                <th data-sortable="true">id</th>
                <th data-sortable="true">Firstname</th>
                <th data-sortable="true">Lastname</th>
                <th data-sortable="true">email</th>
                <th data-sortable="true">thaiID</th>
                <th data-sortable="true">Phone</th>
                <th data-sortable="true">Point</th>
                <th data-sortable="true">FBID</th>
                <th data-sortable="true">is banned?</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($hosts as $value) {
                ?>
                <tr>
                    <th><?= $value['id']?></th>
                    <th><?= $value['fname']?></th>
                    <th><?= $value['lname']?></th>
                    <th><?= $value['email']?> <?= ($value['is_activated']==1)?'✔':'❌'?></th>
                    <th><?= $value['id_card']?></th>
                    <th><?= $value['phone']?></th>
                    <th><?= $value['point']?></th>
                    <th><?= $value['fbid']?></th>
                    <th><?= ($value['suspended']==1)?'✔':'❌'?></th>
                    <th>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_customer" 
                        data-id="<?= $value['id']?>" 
                        data-fname="<?= $value['fname']?>" 
                        data-lname="<?= $value['lname']?>" 
                        data-email="<?= $value['email']?>" 
                        data-thid="<?= $value['id_card']?>" 
                        data-phone="<?= $value['phone']?>" 
                        data-point="<?= $value['point']?>" 
                        data-fbid="<?= $value['fbid']?>" 
                        data-baneed="<?= $value['suspended']?>"><i class="fal fa-edit"></i></button>
                    </th>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
        
    </div>
</div>
<div class="modal fade" id="edit_customer" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_customer_update",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>ID</label>
                <input type="text" class="form-control" name="id">
            </div>
            <div class="form-group">
                <label>First name</label>
                <input type="text" class="form-control" name="fname">
            </div>
            <div class="form-group">
                <label>Last name</label>
                <input type="text" class="form-control" name="lname">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label>point</label>
                <input type="number" class="form-control" name="point">
            </div>
            <div class="form-group">
                <label>phone</label>
                <input type="text" class="form-control" name="phone">
            </div>
            <div class="form-group">
                <label>TH ID</label>
                <input type="text" class="form-control" name="thid">
            </div>
            <div class="form-group">
                <label>FB ID</label>
                <input type="text" class="form-control" name="fbid">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="banned" value="1">
                <label class="form-check-label">Is banned?</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Edit customer</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $('#edit_customer').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('fname')
        var lname = button.data('lname')
        var email = button.data('email')
        var thid = button.data('thid')
        var phone = button.data('phone')
        var point = button.data('point')
        var fbid = button.data('fbid')
        var banned = button.data('banned')
        var modal = $(this)
        $('input[name$="id"]').val(id)
        $('input[name$="fname"]').val(name)
        $('input[name$="lname"]').val(lname)
        $('input[name$="email"]').val(email)
        $('input[name$="point"]').val(point)
        $('input[name$="fbid"]').val(fbid)
        $('input[name$="thid"]').val(thid)
        $('input[name$="lname"]').val(lname)
        $('input[name$="fbid"]').val(fbid)
        if(banned==1){
            $('input[name$="banned"]'). prop("checked", true);
        } else {
            $('input[name$="banned"]'). prop("checked", false);
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