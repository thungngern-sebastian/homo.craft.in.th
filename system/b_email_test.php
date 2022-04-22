<?php
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
if(!empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){

            // $setting=[];
            // $query = _que('SELECT * FROM setting');
            // if(!is_array($query) || @!isset($query['failed'])){
            //     $setting = $query->fetchAll(PDO::FETCH_ASSOC);
            // }
            // $stmp = json_decode($setting[0]['stmp']);
            require_once '../API/PHPMailer.php';
            require_once '../API/SMTP.php';
            require_once '../API/Exception.php';
            require_once '../API/OAuth.php';
            $mail = new PHPMailer(true);
            try {
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
            $mail->Subject = '[Craft.in.th] Test Email';
            /*
            $res = '<html><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
            <div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
            background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
            padding:4rem;">
            <center>
                <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
                    <p>If you can read this OK bye</p>
                </div>
                <div style="color:#fff;padding:2rem;background-color:#111;width:100%;max-width:350px;">'.$end_body.'</div>
                </center>
            </div>
            </center></html>';
            */
            $email_text = ['test.png', $user['fname'].' '.$user['lname'], 'Test Email', 'This is subtitile', 'This is description', 'This is button', 'https://controlpanel.craft.in.th/'];
            $res = str_replace(['{{image}}', '{{customer_full_name}}', '{{title}}', '{{subtitle}}', '{{description}}', '{{button_text}}', '{{button_url}}'], $email_text, file_get_contents(__DIR__.'/../components/email_template.html'));

            $res_alt = 'If you can read this OK bye\n'.$end_body;
            $mail->addAddress($_P['email'], $user['fname'].' '.$user['lname']);
            $mail->Body    = $res;
            $mail->AltBody = $res_alt;
            $mail->send();
            http_response_code(200);
            $data = ['msg' => 'ส่ง Email แล้ว'];
        } catch (Exception $e) {
            $data = ['msg' => $mail->ErrorInfo];
        }
          }
          
        }
    } else {
        unset($_SESSION["username"]);
    }
}