<?php
if(empty($_SESSION['username'])){
    die('<script>window.location.replace("?page=home")</script>');
}
$orders=[];
$query = _que('SELECT * FROM order_history WHERE cusid=? ORDER BY date DESC',[$_SESSION['username']]);
if(!is_array($query) || @!isset($query['failed'])){
    $orders = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="container pb-5">
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar">
                <h4><?= L::orderhistory?></h4>
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
                <th><?= L::info?></th>
                <th><?= L::price?></th>
                <th><?= L::date?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($orders as $value) {
                ?>
                <tr>
                    <td><small><?= $value['ref']?></small><br><?= $value['info']?></td>
                    <td><?= $value['price']?>฿</td>
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