<?php
if(!empty($_P['pay_option']) && !empty($_P['amount']) && is_numeric($_P['amount']) && $_P['amount'] >= 20 && $_P['amount'] <= 150000 && !empty($_SESSION['username'])) {
    if(hcap_check($_P['h-captcha-response'])) {
        $status = 'Failed';
        $ref1 = '';
        $ref2 = '';
        $amount = floor($_P['amount']);
        switch ($_P['pay_option']) {
            case 'KB':
                require_once '../ordinary/K-bank.php';
            break;
            /*
            case 'PP':
                require_once '../ordinary/paypal.php';
            break;
            */
            case 'TW':
                require_once '../ordinary/wallet.php';
            break;
            /*
            case 'RD':
                require_once '../ordinary/redeem.php';
            */
            break;
        }
        if($status == 'Success') {
            if(empty($_P['sandbox_mode'])) {
                $query = _que('INSERT into `topup_history` (`ref1`, `ref2`, `cusid`, `amount`, `type`, `status`) Values (?, ?, ?, ?, ?, ?); UPDATE `customer` SET `point` = `point` + ? WHERE `id` = ?', [
                    $ref1, $ref2, $_SESSION['username'], $amount, $_P['pay_option'], 'Success', $amount, $_SESSION['username']
                ]);
                if(!is_array($query) || @!isset($query['failed'])){
                    http_response_code(200);
                    $data = ['msg' => 'เติมเงินสำเร็จ'];
                } else {
                    $data = ['msg' => $query['msg']];
                }
            } else {
                http_response_code(200);
                $data = ['msg' => 'เติมเงินสำเร็จ'];
            }
        } elseif($status == 'Pending') {
            $query = _que('INSERT into `topup_history` (`ref1`, `ref2`, `cusid`, `amount`, `type`, `status`) Values (?, ?, ?, ?, ?, ?)', [
                $ref1, $ref2, $_SESSION['username'], $amount, $_P['pay_option'], 'Pending'
            ]);
            if(!is_array($query) || @!isset($query['failed'])) {
                http_response_code(200);
                $data = ['msg' => 'แจ้งโอนสำเร็จ รอการยืนยันรายการ'];
            } else {
                $data = ['msg' => $query['msg']];
            }
        } else {
            $data = ['msg' => 'เติมเงินไม่สำเร็จ'];
        }
    } else {
        $data = ['msg' => 'Captcha ไม่ถูกต้อง'];
    }
} else {
    $data = ['msg' => 'Data incorrect!'];
}