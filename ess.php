<?php
// Email Footer
$end_body="<p>Best Regard., Craft.in.th<br>
100/280 Village No.6, Pantai Norasingh, Mueang Samut Sakhon, Samut Sakhon<br>
MOBILE: 096 250 7708<br>
Email: support@craft.in.th<br>
Web: Craft.in.th</p>";

// hCaptcha
$_HCAP_SK='5bad1557-17e5-461a-91ad-71a13f568d9c';
$_HCAP_SECK='0xB36d5e5dFAB78fd230daa874bb62f3720ba2cf51';

date_default_timezone_set('Asia/Bangkok');

$_PDOOO=NULL;
function _conn(){
    global $_PDOOO;
    if($_PDOOO instanceof PDO) {
        return $_PDOOO;
     } else{
        try {
            $_PDOOO = new PDO('mysql:host=localhost;dbname=admin_craft;charset=utf8', 'admin_craft', '7e3Wc9d@x9r7bJ*4');
            $_PDOOO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $_PDOOO;
        }
        catch(PDOException $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            die(json_encode(['msg' => 'Database error : '.$e->getMessage()]));
        }
     }
    
}

function _que($state, $param=[],$fetch_mode=NULL){
    try {
        
        $pdo = _conn();
        $stmt = $pdo->prepare($state);
        $stmt->execute($param);
        return $stmt;

    }
    catch(PDOException $e)
    {
        return ['failed'=>true,
        'msg'=>$e->getMessage( ),
        'code'=>$e->getCode( )];
    }
}
function mail_check($email){
    $data= _req('https://disify.com/api/email/'.$email);
    return $data['disposable'];
}
function hcap_check($res){
    global $_HCAP_SECK;
    $data= _req('https://hcaptcha.com/siteverify',['response'=>$res,'secret'=>$_HCAP_SECK]);
    return $data['success'];
}
function _req($url, $post=[]){
    $handle = curl_init();
    
    curl_setopt($handle,CURLOPT_URL, $url);
    curl_setopt($handle,CURLOPT_RETURNTRANSFER, true);

    if(!empty($post)){
        curl_setopt($handle,CURLOPT_POST, true);
        curl_setopt($handle,CURLOPT_POSTFIELDS, $post);
    }
    
    $data = curl_exec($handle);
    curl_close($handle);
    
    return (is_object(json_decode($data))) ? json_decode($data, true): $data;
}

function formee($stack){
    foreach($stack as $k => $v){
        if(strpos($k, 'markdown') !== false){
            $stack[$k] = $v;
        } else
        if(is_array($v)){
            $stack[$k]=formee($v);
        } else {
            $stack[$k] = htmlspecialchars(strip_tags($v));
        }
    }
    return $stack;
}

function _ranstr($ln=16){
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($ln / strlen($x)))), 1, $ln);
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function payment_transaltor($str){
    switch($str){
        case'KB':
            return 'K-bank';
        break;
        case'PP':
            return 'Paypal';
        break;
        case'TW':
            return 'TrueWallet';
        break;
    }
    return false;
}
function ThaiIDCheckSum($UniqueID){
    $WithoutCheckSum = substr($UniqueID,0,12);
    $CheckSum = substr($UniqueID,-1);
    $CalCheckSum = 0; $j=0;
    while ($j<=12){
            $CalCheckSum += (int)substr($WithoutCheckSum,$j,1)*(13-$j);
            $j++;
    }
    return ((11-($CalCheckSum%11))%10) == $CheckSum ? true : false;
}
$disk_minimumn=40;