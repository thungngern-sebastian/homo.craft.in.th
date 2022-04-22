<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $้history=[];
    $day = (!empty($_GET['D']))?$_GET['D']:0;
    $month = (!empty($_GET['M']))?$_GET['M']:date('n');
    $year = (!empty($_GET['Y']))?$_GET['Y']:date('Y');
    $date = @date("Y-m-d H:i:s", @mktime(0, 0, 0, (($month>0)?$month:1), (($day>0)?$day:1), $year));
    $xxxx = ($day>0)?'AND DAY(topup_history.date) = DAY("'.$date.'")':'';
    $query = _que('SELECT * FROM topup_history
    INNER JOIN customer ON topup_history.cusid = customer.id WHERE YEAR(topup_history.date) = YEAR("'.$date.'") AND MONTH(topup_history.date) = MONTH("'.$date.'")  '.$xxxx.'  ORDER BY topup_history.date DESC');
    if(!is_array($query) || @!isset($query['failed'])){
        $้history = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    $sum=0;
?>
<script>
    function pp(){
        window.location.replace("?page=b_topup_history&D="+$('#p_d').val()+'&M='+$('#p_m').val()+'&Y='+$('#p_y').val());
    }
</script>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF/jspdf.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
<div class="container pb-5">
<div class="card">
    <div class="card-body">
        <div class="toolbar d-flex flex-row">
            <h4>Topup history</h4>
            <div class="ml-2 d-flex flex-row">
                <input class="form-control form-control-sm" type="number" placeholder="D" min="0" max="31" value="<?= (isset($_GET['D']))?$_GET['D']:0?>" id="p_d" onchange="pp()">
                <input class="form-control form-control-sm" type="number" placeholder="M" min="1" max="12" value="<?= (!empty($_GET['M']))?$_GET['M']:date('n')?>" id="p_m" onchange="pp()">
                <input class="form-control form-control-sm" type="number" placeholder="Y" value="<?= (!empty($_GET['Y']))?$_GET['Y']:date('Y')?>" id="p_y" onchange="pp()">
            </div>
        </div>
    <table 
        class="table"
        id="table"
        data-show-export="true"
        data-toggle="table"
        data-toolbar=".toolbar"
        data-sortable="true"
        data-show-columns="true"
        data-pagination="true"
        data-show-columns-toggle-all="true"
        data-search="true"
        data-show-footer="true">
        <thead>
            <tr>
                <th data-sortable="true">REF1</th>
                <th data-sortable="true">REF2</th>
                <th data-sortable="true">Date</th>
                <th data-sortable="true">Status</th>
                <th data-sortable="true">Type</th>
                <th data-sortable="true">Amount</th>
                <th data-sortable="true">User infomation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($้history as $value) {
                ?>
                <tr>
                    <td><small><?= $value['ref1']?></small></td>
                    <td><small><?= $value['ref2']?></small></td>
                    <td><?= $value['date']?></td>
                    <td><?= $value['status']?></td>
                    <td><?= payment_transaltor($value['type'])?></td>
                    <td><?= $value['amount']?>฿</td>
                    <td><?= $value['fname']?> <?= $value['lname']?> #<?= $value['id']?></td>
                </tr>
                <?php
                $sum=($value['status']=='Success'&&$value['type']!='RD')?$sum+$value['amount']:$sum+0;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5"><small>รวมเติมเงินผ่านทั้งหมด</small></th>
                <th><?=$sum?>฿</th>
                <th>ภาษี <?=(($sum*7)/100)?>฿</th>
            </tr>
        </tfoot>
        </table>
    </div>
</div>
</div>