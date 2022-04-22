<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once '../API/PHPMailer.php';
require_once '../API/SMTP.php';
require_once '../API/Exception.php';
require_once '../API/OAuth.php';
if(!empty($_P['email']) && filter_var($_P['email'], FILTER_VALIDATE_EMAIL) && empty($_SESSION['username'])){
    
  if(hcap_check($_P['h-captcha-response'])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where email = ?', [$_P['email']]);
    if(!is_array($query) || @!isset($query['failed'])){
            $user = $query->fetch(PDO::FETCH_ASSOC);
            $ip = get_client_ip();
            $ip_data= unserialize(_req('http://ip-api.com/php/'.$ip));
            $country=($ip_data['status']=='success')?$ip_data['country']:'Unknown';
            $rg=($ip_data['status']=='success')?$ip_data['regionName']:'Unknown';
            $city=($ip_data['status']=='success')?$ip_data['city']:'Unknown';
            
            if(empty($user)){
                http_response_code(200);
                $data = ['msg' => 'ส่ง Email แล้ว'];
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
                $p_key=md5((microtime().rand()).$user['id']);
                _que("UPDATE customer SET reset_key=? WHERE id=?",[$p_key,$user['id']]);
                /*
                $res = '
                <html><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
                <div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
                background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
                padding:4rem;">
                <center>
                    <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
                        <p>สวัสดี <b>'.$user['fname'].' '.$user['lname'].'</b></p>
                        <p>เราได้ส่ง Email นี้มา เพราะคุณต้องการ Reset Passowrd จาก ที่อยู่ดังนี้หรือไม่
                        ให้กดลิ้งนี้</p>
                        <p style="margin-bottom:0px;">IP '.$ip.'</p>
                        <p style="margin-bottom:0px;">Country '.$country.'</p>
                        <p style="margin-bottom:0px;">Region '.$rg.'</p>
                        <p style="margin-bottom:12px;">City '.$city.'</p>
                        <a href="https://controlpanel.craft.in.th/?page=forget&k='.$p_key.'" 
                        style="width:100%; background-color:#007bff;color:#fff;padding: .5rem 1rem;font-size: 1.25rem;border-radius: .3rem; text-decoration:none">
                        Reset password
                        </a>
                        <p>หากไม่ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ</p>
                    </div>
                    <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
                    </center>
                </div>
                </center></html>';
                */

                $email_text = ['user/reset_password.png', $user['fname'].' '.$user['lname'], 'Reset password', 'เราได้ส่ง Email นี้มาเพื่อเนื่องจากมีการกดลืมรหัสผ่าน โดย', 'IP '.$ip.'<br>Country '.$country.'<br>Region '.$rg.'<br>City '.$city.'<br>หากไม่ใช่คุณ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ', 'กดเพื่อ Reset Password', 'https://controlpanel.craft.in.th/?page=forget&k='.$p_key];
                $res = str_replace(['{{image}}', '{{customer_full_name}}', '{{title}}', '{{subtitle}}', '{{description}}', '{{button_text}}', '{{button_url}}'], $email_text, file_get_contents(__DIR__.'/../components/email_template.html'));
                
                $res_alt = 'สวัสดี '.$user['fname'].' '.$user['lname'].' เราได้ส่ง Email นี้มา เพราะคุณต้องการ Reset Passowrd จาก ที่อยู่ดังนี้หรือไม่
                ให้กดลิ้งนี้ IP '.$ip.' Country '.$country.' Region '.$rg.' City '.$city.' https://controlpanel.craft.in.th/?page=forget&k='.$p_key.' หากไม่ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ '.$end_body;
                
                $mail->addAddress($user['email'], $user['fname']);
                $mail->Body    = $res;
                $mail->AltBody = $res_alt;
                $mail->Priority = 1;
                $mail->AddCustomHeader("X-MSMail-Priority: High");
                try {
                    $mail->send();
                    http_response_code(200);
                    $data = ['msg' => 'ส่ง Email แล้ว'];
                }
                catch (Exception $e) {  
                    $data = ['msg' => 'ส่ง Email ไม่สำเร็จ '. $mail->ErrorInfo];
                }
            }
    } else {
        $data = ['msg' => $query['msg']];
    }
} else {
    $data = ['msg' =>'Captcha ไม่ถูกต้อง'];
  }
} else {
    $data=['msg'=>'ข้อมูลไม่ถูกต้อง'];
}
?>