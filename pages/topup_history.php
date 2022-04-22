<?php
if(empty($_SESSION['username'])){
    die('<script>window.location.replace("?page=home")</script>');
}
$historys=[];
$query = _que('SELECT * FROM topup_history WHERE cusid=? ORDER BY date DESC',[$_SESSION['username']]);
if(!is_array($query) || @!isset($query['failed'])){
    $historys = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="container pb-5">
<div class="card">
    <div class="card-body">
        <div class="toolbar">
                <h4><?= L::topuphistory?></h4>
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
                <th>REF1</th>
                <th><?= L::summaryamount?></th>
                <th>Status</th>
                <th><?= L::summarytype?></th>
                <th><?= L::date?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($historys as $value) {
                ?>
                <tr>
                    <td><?= $value['ref1']?></td>
                    <td><?= $value['amount']?>฿</td>
                    <td><?= $value['status']?></td>
                    <td><?= payment_transaltor($value['type'])?></td>
                    <td><?= $value['date']?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
    </div>
</div>
</div>