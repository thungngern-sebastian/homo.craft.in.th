<?php

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
if(!empty($_P['PP-id'])){
    $l=[];
    $query = _que('SELECT * FROM topup_history WHERE ref1 = ?',[$_P['PP-id']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $l = $query->fetch(PDO::FETCH_ASSOC);
    }
    if(empty($l)){
        require_once'../API/paypal/autoload.php';

        //For Production
        $clientId = "AdhdkCSw1AttKZ2oYaN_mxy2n6iPluBBNq2YR98ZiUA3qFgptiqdoroWk0BcyaDsdGJqIklbNHP2liV0";
        $clientSecret = "EPCk508Bw053hnAjzy218EHbZm172kOfs2RKSdGv9xiXbZoMCMq7Dl1OMHoEpb2JGvbLd_jpQi5QCoLc";

        //For Sandbox
        $sandclientId = "Ae0-Qo2EMUDQocDicgt_shO8E8nhod5WW6DN2n_xyje1wP5zAAchmOZFKRoIMYN2rDw3iNrtRIYkiEGH";
        $sandclientSecret = "EOG6LeYvfJDas1HbHVt_YG7Eai1RndLoGwDkXg9N3k8H5N-vMRThC8UTTg2VIcixHU02ivXcX7V0E4B6";
        
        if(empty($_P['sandbox_mode'])){
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($sandclientId, $sandclientSecret);
        }
        $client = new PayPalHttpClient($environment);

        $request = new OrdersCaptureRequest($_P['PP-id']);
        $request->prefer('return=representation');
        try {
            $response = (array) $client->execute($request);
            if($response['statusCode']=='201'){
                $res=$response['result'];
                if($res->status=='COMPLETED'){
                    $status='Success';
                    if(empty($_P['sandbox_mode'])){
                        $amount=($amount-11)-(floor((($amount*5)/100)));
                    } else {
                        $amount=0;
                    }
                    $ref1=$_P['PP-id'];
                }
            }
        }catch (HttpException $ex) {
        }
    }
}