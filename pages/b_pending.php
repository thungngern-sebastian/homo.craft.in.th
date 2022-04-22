<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $้history=[];
    $query = _que('SELECT * FROM topup_history
    INNER JOIN customer ON topup_history.cusid = customer.id WHERE topup_history.status="Pending" ORDER BY topup_history.date DESC');
    if(!is_array($query) || @!isset($query['failed'])){
        $้history = $query->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<div class="container pb-5">
<div class="card">
    <div class="card-body">
        <div class="toolbar">
            <h4>Topup Pending</h4>
        </div>
    <table class="table">
        <thead>
            <tr>
                <th>REF1</th>
                <th>REF2</th>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>User infomation</th>
                <th></th>
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
                    <td><?= payment_transaltor($value['type'])?></td>
                    <td><?= $value['amount']?>฿</td>
                    <td><?= $value['fname']?> <?= $value['lname']?> #<?= $value['id']?></td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="ajax('b_update_pending',{'ref':'<?= $value['ref1']?>','stat':'Success','uid':'<?= $value['id']?>','am':<?= $value['amount']?>})">Y</button>
                        <button type="button" class="btn btn-danger" onclick="ajax('b_update_pending',{'ref':'<?= $value['ref1']?>','stat':'Failed'})">N</button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        </table>
    </div>
</div>
</div>