<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if(!empty($_SESSION['username']) && !empty($_P['id']) && is_numeric($_P['id']) & $_P['id'] > 0) {
    $pdo = _conn();
    $query = _que('SELECT * FROM `customer` where `id` = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)) {
            unset($_SESSION["username"]);
        } elseif (empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
            $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
            $is_activated=($user['is_activated'] == 1 && !empty($user['is_activated']))?true:false;
            $is_suspended=($user['suspended'] == 1 && !empty($user['suspended']))?true:false;
        
            if(!$is_suspended && $is_activated) {
                $query = _que('SELECT * FROM `hosting` WHERE `cusid` = ? AND `id` = ?', [$_SESSION['username'], $_P['id']]);
                if(!is_array($query) || @!isset($query['failed'])) {
                    $hosting = $query->fetch(PDO::FETCH_ASSOC);
                    if(!empty($hosting)) {
                        require_once ('../API/PleskAPI.php');
                        $plesk = new kiznick_Plesk_API('https://hosting.drite.in.th:8443/', 'admin', 'limited3AB@@#');
                        $client = $plesk->request('DELETE', 'clients/'.$hosting['cid'], []);
                        if($client['code'] == 200) {
                            $ip = get_client_ip();
                            $ip_data =  unserialize(_req('http://ip-api.com/php/'.$ip));
                            $country = ($ip_data['status'] == 'success') ? $ip_data['country'] : 'Unknown';
                            $rg = ($ip_data['status'] == 'success') ? $ip_data['regionName'] : 'Unknown';

                            _que('INSERT INTO log (data) VALUES (?)', ["DELETE Hosting '{$_P['ref']}' BY {$ip} {$country} {$rg} {$city}"]);
                            
                            _que('DELETE FROM `hosting` WHERE `id` = ? and `cusid` = ?;', [$_P['id'], $_SESSION['username']]);

                            require_once '../API/PHPMailer.php';
                            require_once '../API/SMTP.php';
                            require_once '../API/Exception.php';
                            require_once '../API/OAuth.php';
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
                            $mail->Subject = '[Craft.in.th] Delete Hosting';
                            /*
                            $res = '<html><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
                            <div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
                            background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
                            padding:4rem;">
                            <center>
                                <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
                                    <p>สวัสดี</p>
                                    <p>เราได้ส่ง Email นี้มาเนื่องจาก Hosting ของคุณถูกลบโดย</p>
                                    <p style="margin-bottom:0px;">IP '.$ip.'</p>
                                    <p style="margin-bottom:0px;">Country '.$country.'</p>
                                    <p style="margin-bottom:0px;">Region '.$rg.'</p>
                                    <p style="margin-bottom:12px;">City '.$city.'</p>
                                    <p>หากคุณไม่ได้เป็นผู้ดำเนินการ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ</p>
                                </div>
                                <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
                                </center>
                            </div>
                            </center></html>';
                            */
                            
                            $email_text = ['cloud/destroy.png', $user['fname'].' '.$user['lname'], 'Delete Hosting', 'เราได้ส่ง Email นี้มาเนื่องจาก Hosting ของคุณถูกลบโดย', 'IP '.$ip.'<br>Country '.$country.'<br>Region '.$rg.'<br>City '.$city.'<br>หากไม่ใช่คุณ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ', 'ติดต่อเรา', 'https://www.facebook.com/craftstudioofficial'];
                            $res = str_replace(['{{image}}', '{{customer_full_name}}', '{{title}}', '{{subtitle}}', '{{description}}', '{{button_text}}', '{{button_url}}'], $email_text, file_get_contents(__DIR__.'/../components/email_template.html'));
                
                            $res_alt = 'สวัสดี เราได้ส่ง Email นี้มาเนื่องจาก Hosting ของคุณถูกลบโดย IP '.$ip.' Country '.$country.' Region '.$rg.' City '.$city.' หากคุณไม่ได้เป็นผู้ดำเนินการ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ '.$end_body;
                            
                            $mail->addAddress($user['email'], $user['fname'].' '.$user['lname']);
                            $mail->Body    = $res;
                            $mail->AltBody = $res_alt;
                            
                            $mail->Priority = 1;
                            $mail->AddCustomHeader("X-MSMail-Priority: High");
                            $mail->send();
                
                            http_response_code(200);
                            $data = ['msg' => 'สำเร็จ', 'eval' => 'window.location.replace("?page=home")'];
                        } else {
                            $data = ['msg' => 'ไม่สามารถลบ Hosting ได้ในขณะนี้'];
                        }
                    }
                } else {
                    $data = ['msg' => $query['msg']];
                }
            }
        }
    } else {
      $data = ['msg' => $query['msg']];
    }
} else {
    $data = ['msg' => 'Data inccorect!'];
}