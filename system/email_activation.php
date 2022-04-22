<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once '../API/PHPMailer.php';
require_once '../API/SMTP.php';
require_once '../API/Exception.php';
require_once '../API/OAuth.php';
if(!empty($_SESSION["username"])){
    if(hcap_check($_P['h-captcha-response'])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($user)){
                unset($_SESSION["username"]);
            } else {
                $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
                $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
                $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
                
                if(!$is_activated && !$is_suspended){
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
                    $mail->Subject = '[Craft.in.th] ยืนยันบัญชีของคุณ';
                    $p_key=md5((microtime().rand()).$_SESSION["username"]);
                    _que("UPDATE customer SET activate_key=? WHERE id=?",[$p_key,$_SESSION['username']]);
                    /*
                    $res = '
                    <html><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
                    <div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
                    background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
                    padding:4rem;">
                    <center>
                        <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
                            <p>สวัสดี <b>'.$user['fname'].' '.$user['lname'].'</b></p>
                            <p>เราได้ส่ง Email นี้มา เพราะคุณต้องการยืนยัน Email หรือไม่<br>หากใช่
                            ให้กดลิ้งนี้ (กรุณาเข้าสู่ระบบด้วยนะครับ)</p>
                            <a href="https://controlpanel.craft.in.th/?page=email_activation&k='.$p_key.'" 
                            style="width:100%; background-color:#007bff;color:#fff;padding: .5rem 1rem;font-size: 1.25rem;border-radius: .3rem; text-decoration:none">
                            ยืนยันตัวตน
                            </a>
                            <p>หากไม่ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ</p>
                        </div>
                        <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
                        </center>
                    </div>
                    </center></html>';
                    */

                    $email_text = ['user/email_activation.png', $user['fname'].' '.$user['lname'], 'ยืนยันบัญชีของคุณ', 'เราได้ส่ง Email นี้มาเพราะคุณต้องการยืนยัน Email', 'หากไม่ใช่คุณ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ', 'ยืนยันตัวตน', 'https://controlpanel.craft.in.th/?page=email_activation&k='.$p_key];
                    $res = str_replace(['{{image}}', '{{customer_full_name}}', '{{title}}', '{{subtitle}}', '{{description}}', '{{button_text}}', '{{button_url}}'], $email_text, file_get_contents(__DIR__.'/../components/email_template.html'));
                    

                    $res_alt = 'สวัสดี <b>'.$user['fname'].' '.$user['lname'].' เราได้ส่ง Email นี้มา เพราะคุณต้องการยืนยัน Email หรือไม่
                    หากใช่ ให้กดลิ้งนี้ (กรุณาเข้าสู่ระบบด้วยนะครับ) https://controlpanel.craft.in.th/?page=email_activation&k='.$p_key.'
                    หากไม่ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ '.$end_body;
                    
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
                    
                } else {
                    $data = ['msg' => 'This account doesn\'t met requirement'];
                }
            }
    } else {
        $data = ['msg' => $query['msg']];
    }
} else {
    $data = ['msg' => 'Capcha ไม่ถูกต้อง'];
}
}