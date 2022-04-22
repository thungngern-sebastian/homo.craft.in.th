<?php
if(!$is_admin){
    die('<script>window.location.replace("?page=home")</script>');
}
require_once '../API/KasikornBank.class.php';
$kbank = new KasikornBank("jamillejung", "limited1AB@@#", "../cookie.txt");
if (!$kbank->CheckSession()) {
    $kbank->Login();
}
$TodayStatement = $kbank->GetTodayStatement("028-8-65961-3");
?>
<div class="container pb-5">
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
    <div class="toolbar">
        <h3 class="mb-0">K-Bank Statement</h3>
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
                <th data-sortable="true">Timestamp</th>
                <th data-sortable="true">Channel</th>
                <th data-sortable="true">Type</th>
                <th data-sortable="true">Deposit (THB)</th>
                <th data-sortable="true">A/C number</th>
                <th data-sortable="true">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($TodayStatement as $value) {
                if(empty($value['Deposit (THB)'])){
                    continue;
                }
                ?>
                <tr>
                    <td><?= $value['Date/Time']?></td>
                    <td><?= $value['Channel']?></td>
                    <td><?= $value['Transaction Type']?></td>
                    <td><?= $value['Deposit (THB)']?></td>
                    <td><?= $value['A/C Number']?></td>
                    <td><?= $value['Details']?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
    </div>
</div>
        </div>