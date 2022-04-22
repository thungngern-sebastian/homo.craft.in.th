<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once '../API/PHPMailer.php';
require_once '../API/SMTP.php';
require_once '../API/Exception.php';
require_once '../API/OAuth.php';
if(!empty($_P['password']) && !empty($_P['confirm_password']) && $_P['confirm_password']===$_P['password'] && !empty($_P['key']) && empty($_SESSION['username'])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where reset_key = ?', [$_P['key']]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        $ip = get_client_ip();
        $ip_data= unserialize(_req('http://ip-api.com/php/'.$ip));
        $country=($ip_data['status']=='success')?$ip_data['country']:'Unknown';
        $rg=($ip_data['status']=='success')?$ip_data['regionName']:'Unknown';
        $city=($ip_data['status']=='success')?$ip_data['city']:'Unknown';
        
        if(empty($user)){
            $data = ['msg' => 'KEY NOT FOUND'];
        } else {
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
            $mail->Host       = 'craft.in.th';
            $mail->CharSet = "UTF-8";   
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no-reply@drite.in.th';
            $mail->Password   = '$66tujL6';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('no-reply@drite.in.th', 'No-reply');
            $mail->isHTML(true);
            $mail->Subject = '[Craft.in.th] Reset password';
            $salt = _ranstr();
            $hash = hash('sha256', hash('sha256', $_P['password']) . $salt);
            $pass = htmlspecialchars(strip_tags("\$SHA\${$salt}\${$hash}"));
            _que("UPDATE customer SET reset_key='',password=? WHERE id=?",[$pass,$user['id']]);
            /*
            $res = '<html><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
            <div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
            background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
            padding:4rem;">
            <center>
                <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
                    <p>สวัสดี <b>'.$user['fname'].' '.$user['lname'].'</b></p>
                    <p>เราได้ส่ง Email นี้มา เพราะรหัสของคุณโดน Reset แล้ว โดย</p>
                    <p style="margin-bottom:0px;">IP '.$ip.'</p>
                    <p style="margin-bottom:0px;">Country '.$country.'</p>
                    <p style="margin-bottom:0px;">Region '.$rg.'</p>
                    <p style="margin-bottom:12px;">City '.$city.'</p>
                    <p>หากไม่ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ</p>
                </div>
                <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
                </center>
            </div>
            </center></html>';
            */
            $email_text = ['user/reset_password.png', $user['fname'].' '.$user['lname'], 'Reset password', 'เราได้ส่ง Email นี้มา เพราะรหัสของคุณโดน Reset แล้ว โดย', 'IP '.$ip.'<br>Country '.$country.'<br>Region '.$rg.'<br>City '.$city.'<br>หากไม่ใช่คุณ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ', 'ติดต่อเรา', 'https://www.facebook.com/craftstudioofficial'];
            $res = str_replace(['{{image}}', '{{customer_full_name}}', '{{title}}', '{{subtitle}}', '{{description}}', '{{button_text}}', '{{button_url}}'], $email_text, file_get_contents(__DIR__.'/../components/email_template.html'));

            $res_alt = 'เราได้ทำการ Reset Password แล้ว';
            
            $mail->addAddress($user['email'], $user['fname']);
            $mail->Body    = $res;
            $mail->AltBody = $res_alt;
            $mail->send();
            http_response_code(200);
            $data = ['msg' => 'สำเร็จ','eval'=>'window.location.replace("?page=home")'];
        }
    } else {
        $data = ['msg' => $query['msg']];
    }
    
} else {
    $data=['msg'=>'ข้อมูลไม่ถูกต้อง'];
}
?>