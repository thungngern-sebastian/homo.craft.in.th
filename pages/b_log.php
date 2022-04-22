<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $logs=[];
    $query = _que('SELECT * FROM log');
    if(!is_array($query) || @!isset($query['failed'])){
        $logs = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>
    <div class="container pb-5">
    <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar">
        <h3 class="mb-0">Logs</h3>
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
                <th data-sortable="true">data</th>
                <th data-sortable="true">date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($logs as $value) {
                ?>
                <tr>
                    <th><?= $value['id']?></th>
                    <th><?= $value['data']?></th>
                    <th><?= $value['date']?></th>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
    </div>
</div>
</div>