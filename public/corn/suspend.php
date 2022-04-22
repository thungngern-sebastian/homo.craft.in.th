<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
@require_once __DIR__."/../../ess.php";
@require_once __DIR__."/../../API/Xen.php";
require_once __DIR__.'/../../API/PHPMailer.php';
require_once __DIR__.'/../../API/SMTP.php';
require_once __DIR__.'/../../API/Exception.php';
require_once __DIR__.'/../../API/OAuth.php';

$suspend=[];
$data=[];
$host=[];
$customer=[];
$ip=[];

// $setting=[];
// $query = _que('SELECT * FROM setting');
// if(!is_array($query) || @!isset($query['failed'])){
//     $setting = $query->fetchAll(PDO::FETCH_ASSOC);
// }
// $stmp = json_decode($setting[0]['stmp']);

$mail = new PHPMailer(true);
$mail->isSMTP();
// $mail->Host       = $stmp->host;
// $mail->CharSet = "UTF-8";   
// $mail->SMTPAuth   = true;
// $mail->Username   = $stmp->username;
// $mail->Password   = $stmp->password;
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
// $mail->Port       = 587;
// $mail->setFrom($stmp->mail, $stmp->title);

$mail->Host       = '43.229.151.97';
$mail->CharSet = "UTF-8";   
$mail->SMTPAuth   = true;
$mail->Username   = 'no-reply@drite.in.th';
$mail->Password   = '$66tujL6';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;
$mail->setFrom('no-reply@drite.in.th', 'No-reply');
$mail->isHTML(true);
$mail->Subject = '[Craft.in.th] ต่ออายุ Cloud เรียบร้อย';
$res = '<html><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
<div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
padding:4rem;">
<center>
    <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
        <p>สวัสดี</p>
        <p>เราได้ส่ง Email นี้มา เพราะ ระบบได้ต่ออายุ cloud ของคุณแล้ว</p>
    </div>
    <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
    </center>
</div>
</center></html>';
$res_alt = 'สวัสดี เราได้ส่ง Email นี้มา เพราะ ระบบได้ต่ออายุ cloud ของคุณแล้ว '.$end_body;

$query=_que('SELECT vm.host,vm.* FROM vm WHERE vm.timestamp < NOW() AND vm.unlimited=0');
if(!is_array($query) || @!isset($query['failed'])){
    $data = $query->fetchAll(PDO::FETCH_GROUP);
}

$query=_que('SELECT hosts.host,hosts.* FROM hosts LEFT JOIN vm
ON hosts.host = vm.host WHERE vm.timestamp < NOW() AND vm.unlimited=0');
if(!is_array($query) || @!isset($query['failed'])){
    $host = $query->fetchAll(PDO::FETCH_UNIQUE);
}

$query=_que('SELECT customer.id,customer.point,customer.email FROM customer LEFT JOIN vm
ON customer.id = vm.cusid WHERE vm.timestamp < NOW() AND vm.unlimited=0');
if(!is_array($query) || @!isset($query['failed'])){
    $customer = $query->fetchAll(PDO::FETCH_UNIQUE);
}
$query=_que('SELECT ip_address.uuid,ip_address.* FROM ip_address LEFT JOIN vm
ON ip_address.uuid = vm.ref WHERE vm.timestamp < NOW() AND vm.unlimited=0');
if(!is_array($query) || @!isset($query['failed'])){
    $ip = $query->fetchAll(PDO::FETCH_UNIQUE);
}
foreach ($data as $key => $value) {
    $xen = new PsXenAPI($key,$host[$key]['username'],$host[$key]['password']);
    if(!empty($xen->id_session)){
        foreach ($value as $x) {
            if($x['autorenew']==0){ $suspend[$key][]=$x; continue; }
            $opq_ref=$xen::apref($x['ref']);
            if(empty($ip[$x['ref']])){
                $xen->rq('VM.hard_shutdown',[$opq_ref]);
                continue;;
            
            }
            $price=$x['base_price'];
            if(!empty($customer[$x['cusid']])){
                if($customer[$x['cusid']]['point']>=$price){
                    $customer[$x['cusid']]['point']=$customer[$x['cusid']]['point']-$price;
                    $date = strtotime("+{$x['lenght']} day");
                    $date = date('Y-m-d H:i:s',$date);
                    $name= $ip[$x['ref']]['ipv4'].' - '.$customer[$x['cusid']]['email'].' - '.$date;
                    $s = $xen->rq('VM.set_name_label',[$opq_ref,$name]);
                    _que('UPDATE customer SET point=point-? WHERE id=?;UPDATE vm SET timestamp=DATE_ADD(timestamp, INTERVAL '.$x['lenght'].' DAY),pause=0 WHERE ref=?',[$price,$x['cusid'],$x['ref']]);
					$info = "Rent cloud {$ip[$x['ref']]['ipv4']} {$x['plan']} ( {$x['cpu']}C RAM {$x['ram']} GB DISK {$x['disk']} GB ) {$x['lenght']} Day {$x['os']} {$x['distro']} (SYSTEM)";
                    _que('INSERT into order_history (ref,cusid,price,info) values (?,?,?,?)',[_ranstr(32),$x['cusid'],$price,$info]);
                    $mail->ClearAddresses();
                    $mail->ClearCCs();
                    $mail->ClearBCCs();
                    $mail->addAddress($customer[$x['cusid']]['email']);
                    $mail->Body    = $res;
                    $mail->AltBody = $res_alt;
                    $mail->send();
                    _que('INSERT INTO log (data) VALUES (?)',["RENEW '{$x['ref']}' BY SYSTEM"]);
    
                } else {
                    $suspend[$key][]=$x;
                }
                
            }
        }
    }
}
$del=[];
$mail->Subject = '[Craft.in.th] Cloud หมดอายุแล้ว';
$res = '<center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
<div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
padding:4rem;">
<center>
    <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
        <p>สวัสดี</p>
        <p>เราได้ส่ง Email นี้มา เพราะ cloud หมดอายุของคุณแล้ว ถ้าหากเกิน 6 ชั่วโมงเราจะลบออกทันที</p>
    </div>
    <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
    </center>
</div>
</center>';
$res_alt = 'สวัสดี เราได้ส่ง Email นี้มา เพราะ ระบบได้ต่ออายุ cloud ของคุณแล้ว ถ้าหากเกิน 6 ชั่วโมงเราจะลบออกทันที '.$end_body;
$xxxcesderer = _que('SELECT CURRENT_TIMESTAMP');
$dd = $xxxcesderer->fetch(PDO::FETCH_GROUP);
$pppppp = strtotime($dd['CURRENT_TIMESTAMP']);
foreach ($suspend as $key => $value) {
    $xen = new PsXenAPI($key,$host[$key]['username'],$host[$key]['password']);
    if(!empty($xen->id_session)){
        foreach ($value as $x) {
            
            $opq_ref=$xen::apref($x['ref']);
            _que('UPDATE vm SET pause=1 WHERE ref=?',[$x['ref']]);
            $xen->rq('VM.hard_shutdown',[$opq_ref]);
            $tmp_hr = floor(($pppppp - strtotime($x['timestamp']))/ ( 60 * 60 ));
            if($tmp_hr>=6){
                $del[$key][]=$x;
            } else if($x['pause']==0){
                    $mail->ClearAddresses();
                    $mail->ClearCCs();
                    $mail->ClearBCCs();
                    $mail->addAddress($customer[$x['cusid']]['email']);
                    $mail->Body    = $res;
                    $mail->AltBody = $res_alt;
                    $mail->send();
                }
            }
    }
}
$mail->Subject = '[Craft.in.th] Cloud ของคุณโดนลบแล้ว';
$res = '<center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
<div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
padding:4rem;">
<center>
    <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
        <p>สวัสดี</p>
        <p>เราได้ส่ง Email นี้มา เพราะ cloud หมดอายุของคุณแล้ว ระบบจึงทำการลบออกทันที</p>
    </div>
    <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
    </center>
</div>
</center>';
$res_alt = 'สวัสดี เราได้ส่ง Email นี้มา เพราะ cloud หมดอายุของคุณแล้ว ระบบจึงทำการลบออกทันที '.$end_body;
foreach ($del as $key => $value) {
    $xen = new PsXenAPI($key,$host[$key]['username'],$host[$key]['password']);
    if(!empty($xen->id_session)){
        foreach ($value as $x) {
            $query = _que('DELETE FROM vm WHERE ref=?;UPDATE ip_address SET available = 0 , uuid="" WHERE uuid=?;',[$x['ref'],$x['ref']]);
            if(!is_array($query) || @!isset($query['failed'])){
                $keyzz = PsXenAPI::apref($x['ref']);
                $data=$xen->rq('VM.get_record', [$keyzz])['Value'];
                if($data["power_state"] == 'Running'){
                    $xen->rq('VM.hard_shutdown', [$keyzz]);
                }
                foreach ($data['VBDs'] as $vbd) {
                    $vbdx=$xen->rq('VBD.get_record', [$vbd])['Value'];
                    if($vbdx['type']=='Disk'){
                        $xen->rq('VDI.destroy', [$vbdx['VDI']]);
                    }
                }
                $xen->rq('VM.destroy', [$keyzz]);
                $mail->ClearAddresses();
                $mail->ClearCCs();
                $mail->ClearBCCs();
                $mail->addAddress($customer[$x['cusid']]['email']);
                $mail->Body    = $res;
                $mail->AltBody = $res_alt;
                $mail->send();
                _que('INSERT INTO log (data) VALUES (?)',["DELETE '{$x['ref']}' OF '{$customer[$x['cusid']]['email']}' BY SYSTEM"]);
            } else {
                var_dump($query);
            }
        }
    }
}
