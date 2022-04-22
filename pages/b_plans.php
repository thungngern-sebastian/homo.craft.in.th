<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $plans=[];
    $query = _que('SELECT * FROM plans');
    if(!is_array($query) || @!isset($query['failed'])){
        $plans = $query->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<div class="container pb-5">
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar d-flex flex-row">
        <h3 class="mb-0">Plans</h3>
        <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#add_plan"
            data-plan="" 
            data-cpu="" 
            data-ram="" 
            data-disk="" 
            data-public=""><i class="fal fa-plus"></i></button>
        </div>
        <table 
        class="table"
        id="table"
        data-toggle="table"
        data-toolbar=".toolbar"
        data-sortable="true"
        data-show-columns="true"
        data-show-columns-toggle-all="true"
        data-search="true">
        <thead>
            <tr>
                <th data-sortable="true">Plan</th>
                <th data-sortable="true">Cpu</th>
                <th data-sortable="true">Ram (GB)</th>
                <th data-sortable="true">Disk (GB)</th>
                <th data-sortable="true">Price</th>
                <th data-sortable="true">Is public?</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($plans as $value) {
                ?>
                <tr>
                    <th><?= $value['plan']?></th>
                    <th><?= $value['cpu']?></th>
                    <th><?= $value['ram']?></th>
                    <th><?= $value['disk']?></th>
                    <th><?= $value['price']?></th>
                    <th><?= ($value['public']==1)?'✔':'❌'?></th>
                    <th>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_plan" 
                        data-plan="<?= $value['plan']?>" 
                        data-cpu="<?= $value['cpu']?>" 
                        data-ram="<?= $value['ram']?>" 
                        data-disk="<?= $value['disk']?>" 
                        data-price="<?= $value['price']?>" 
                        data-public="<?= $value['public']?>"><i class="fal fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_plan" data-plan="<?= $value['plan']?>"><i class="fal fa-trash"></i></button></td>
                    </th>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
        
    </div>
</div>
<div class="modal fade" id="add_plan" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_plan_update",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>Plan name</label>
                <input type="text" class="form-control" name="plan">
            </div>
            <div class="form-group">
                <label>Cpu</label>
                <input type="number" class="form-control" name="cpu" min=1>
            </div>
            <div class="form-group">
                <label>Ram (GB)</label>
                <input type="number" class="form-control" name="ram" min=1>
            </div>
            <div class="form-group">
                <label>Disk (GB)</label>
                <input type="number" class="form-control" name="disk" min=40>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" class="form-control" name="price" min=0>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="public" value="1">
                <label class="form-check-label">Is public</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add/Edit Plan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="remove_plan" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Remove Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_plan_remove",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>Plan name</label>
                <input type="text" class="form-control form-control-sm" name="plan" readonly>
            </div>
            <p>* This only remove from database</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Remove Plan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $('#add_plan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var plan = button.data('plan')
        var cpu = button.data('cpu')
        var ram = button.data('ram')
        var disk = button.data('disk')
        var price = button.data('price')
        var public = button.data('public')
        var modal = $(this)
        $('input[name$="plan"]').val(plan)
        $('input[name$="cpu"]').val(cpu)
        $('input[name$="ram"]').val(ram)
        $('input[name$="disk"]').val(disk)
        $('input[name$="price"]').val(price)
        if(public==1){
            $('input[name$="public"]'). prop("checked", true);
        } else {
            $('input[name$="public"]'). prop("checked", false);
        }
    });
    $('#remove_plan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var plan = button.data('plan')
        var modal = $(this)
        $('input[name$="plan"]').val(plan)
    })
</script>
</div>