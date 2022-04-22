<?php
if(!empty($_P['tw_voucher_id']) && strlen($_P['tw_voucher_id']) == 18 && preg_match('/[a-zA-Z0-9 ]/', $_P['tw_voucher_id'])) {
    $data = [
        "mobile" => '0646616749',
        "voucher_hash" => $_P['tw_voucher_id']
    ];
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_HTTPHEADER     => array(
            'Content-Type: application/json'
        ),
        CURLOPT_URL            => 'https://gift.truemoney.com/campaign/vouchers/'.$data['voucher_hash'].'/redeem',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data)
    ));
    $pay = json_decode(curl_exec($ch));
    curl_close($ch);
    if($ch !== false) {
        if($pay->status->code == 'SUCCESS') {
            $amount = floatval(preg_replace('/[^\d.]/', '', $pay->data->my_ticket->amount_baht));
            $status = 'Success';
            $ref1 = $data['voucher_hash'];
        } else {
            $amount = 0;
            $status = 'Fail';
        }
    } else {
        $amount = 0;
        $status = 'Fail';
    }
}