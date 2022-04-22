<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $ips=[];
    $query = _que('SELECT * FROM ip_address');
    if(!is_array($query) || @!isset($query['failed'])){
        $ips = $query->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<div class="container pb-5">
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar d-flex flex-row">
        <h3 class="mb-0">IP</h3>
        <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#add_ip"
        data-ip="" 
        data-sn="" 
        data-bc="" 
        data-sm="" 
        data-gw="" 
        data-uuid="" 
        data-av=""><i class="fal fa-plus"></i></button>
        <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#add_multi_ip"><i class="fal fa-plus"></i> Multi</button>
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
                <th data-sortable="true">IPV4</th>
                <th data-sortable="true">Subnet</th>
                <th data-sortable="true">Boardcast</th>
                <th data-sortable="true"h>Submark</th>
                <th data-sortable="true">gateway</th>
                <th data-sortable="true">uuid</th>
                <th data-sortable="true">Is used?</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($ips as $value) {
                ?>
                <tr>
                    <th><?= $value['ipv4']?></th>
                    <th><?= $value['subnet']?></th>
                    <th><?= $value['boardcast']?></th>
                    <th><?= $value['submark']?></th>
                    <th><?= $value['gateway']?></th>
                    <th><?= $value['uuid']?></th>
                    <th><?= ($value['available']==1)?'✔':'❌'?></th>
                    <th>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_ip" 
                        data-ip="<?= $value['ipv4']?>" 
                        data-sn="<?= $value['subnet']?>" 
                        data-bc="<?= $value['boardcast']?>" 
                        data-sm="<?= $value['submark']?>" 
                        data-gw="<?= $value['gateway']?>" 
                        data-uuid="<?= $value['uuid']?>" 
                        data-av="<?= $value['available']?>"><i class="fal fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_ip" data-ip="<?= $value['ipv4']?>"><i class="fal fa-trash"></i></button></td>
                    </th>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="add_ip" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit IP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_ip_update",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>IPV4</label>
                <input type="text" class="form-control" name="ipv4">
            </div>
            <div class="form-group">
                <label>subnet</label>
                <input type="text" class="form-control" name="subnet">
            </div>
            <div class="form-group">
                <label>boardcast</label>
                <input type="text" class="form-control" name="boardcast">
            </div>
            <div class="form-group">
                <label>submark</label>
                <input type="text" class="form-control" name="submark">
            </div>
            <div class="form-group">
                <label>gateway</label>
                <input type="text" class="form-control" name="gateway">
            </div>
            <div class="form-group">
                <label>uuid</label>
                <input type="text" class="form-control" name="uuid">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="available" value="1">
                <label class="form-check-label">Is used?</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add/Edit IP</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="add_multi_ip" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit IP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_ip_multi_add",$(this).serialize());return false;'>
        <div class="modal-body">
            <div class="form-group">
                <label>IPV4</label>
                <div class="form-row d-flex flex-row align-items-end">
                <div class="col w-100">
                    <input type="text" class="form-control form-control-sm" name="ip1">
                </div>
                <div class="flex-shrink-1">
                    .
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" name="ip2">
                </div>
                <div class="flex-shrink-1">
                    .
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" name="ip3">
                </div>
                <div class="flex-shrink-1">
                    .
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" name="ip4">
                </div>
                <div class="flex-shrink-1">
                    -
                </div>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" name="ip5">
                </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>subnet</label>
                <input type="text" class="form-control" name="subnet">
            </div>
            <div class="form-group">
                <label>boardcast</label>
                <input type="text" class="form-control" name="boardcast">
            </div>
            <div class="form-group">
                <label>submark</label>
                <input type="text" class="form-control" name="submark">
            </div>
            <div class="form-group">
                <label>gateway</label>
                <input type="text" class="form-control" name="gateway">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="available" value="1">
                <label class="form-check-label">Is used?</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add/Edit IP</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="remove_ip" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Remove IP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onSubmit='ajax("b_ip_remove",$(this).serialize());return false;'>
        <div class="modal-body">
        <div class="form-group">
                <label>IPV4</label>
                <input type="text" class="form-control" name="ipv4" readonly>
            </div>
            <p>* This only remove from database</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Remove IP</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $('#add_ip').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var ipv4 = button.data('ip')
        var sn = button.data('sn')
        var bc = button.data('bc')
        var sm = button.data('sm')
        var gw = button.data('gw')
        var uuid = button.data('uuid')
        var av = button.data('av')
        var modal = $(this)
        $('input[name$="ipv4"]').val(ipv4)
        $('input[name$="subnet"]').val(sn)
        $('input[name$="boardcast"]').val(bc)
        $('input[name$="submark"]').val(sm)
        $('input[name$="gateway"]').val(gw)
        $('input[name$="uuid"]').val(uuid)
        if(av==1){
            $('input[name$="available"]'). prop("checked", true);
        } else {
            $('input[name$="available"]'). prop("checked", false);
        }
    });
    $('#remove_ip').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var ipv4 = button.data('ip')
        var modal = $(this)
        $('input[name$="ipv4"]').val(ipv4)
    })
</script>
</div>