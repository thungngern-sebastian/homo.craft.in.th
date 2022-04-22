<?php
http_response_code(200);
if(!empty($_G['length']) && is_numeric($_G['length']) && in_array($_G['length'], [1, 30, 365]) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM `hosting_plans`;', []);
    if(!is_array($query) || @!isset($query['failed'])) {
        $plan = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <h5><?= L::plan?></h5>
        <div class="row" id="plan_selector">
    <?php
        foreach ($plan as $k => $v):
            $length = [
                1 => 'daily',
                30 => 'monthly',
                365 => 'yearly'
            ];
            $price = $v[$length[$_G['length']]];
    ?>
            <div class="col-lg-3">
                <div class="box mb-2">
                    <button class="btn btn-link px-0 btn-sm btn btn-block text-reset text-decoration-none" onclick="plan($(this), <?=$v['id']?>, <?=$price?>)">
                        <div class="px-1 py-2 text-center">
                            <i class="fas fa-server h1 mb-0"></i>
                            <p class="mb-0" style="font-size:12px;"><?=$v['plesk_plan']?></p>
                            <p style="font-size:12px; line-height:14px;" class="mb-0 mt-2">
                                <i class="fal fa-globe"></i> <?=$v['domain']?> Domain</br>
                                <i class="fas fa-database"></i> <?=$v['dbcount']?> Database</br>
                                <i class="fas fa-envelope"></i> <?=$v['email']?> Email Account</br>
                                <i class="fal fa-globe"></i> Bandwidth <?=$v['traffic']?> GB</br>
                                <i class="fal fa-hdd"></i> <?=$v['disk']?> GB
                            </p>
                            <div class="px-1 py-2 text-center">
                                <p class="mb-0"><?=$v['daily']?>฿ / <?= L::day?></p>
                                <p class="mb-0"><?=$v['monthly']?>฿ / <?= L::month?></p>
                                <p class="mb-0"><?=$v['yearly']?>฿ / Year</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
    <?php
        endforeach;
    ?>
        </div>
        <h5>Domain</h5>
        <div class="form-group">
            <label>Domain <span data-alert="domain_invalid" style="display: none;" class="badge badge-pill badge-danger">Domain invalid</span></label>
            <input type="text" class="form-control" oninput="validDomain($(this).val())" data-input="domain" spellcheck="false" data-ms-editor="true" autocomplete="off">
        </div>
    <?php
        die();
    } else {
        die('<div class="alert alert-danger mt-3">ไม่สามารถดึงข้อมูลได้ !</div>');
    }
}