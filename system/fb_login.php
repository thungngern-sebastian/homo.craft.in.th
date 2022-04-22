
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
if(empty($_SESSION['username']) && !empty($_P['token'])){

    require_once '../API/facebook/autoload.php';
    $fb = new Facebook\Facebook([
        'app_id' => '1061786614275267',
        'app_secret' => '839d2a560aac3451638598caef428f4c',
        'default_graph_version' => 'v6.0',
        ]);    
    try {
        $response = $fb->get(
            '/me?fields=id,last_name,first_name,picture.width(100).height(100)',
            $_P['token']
        );
    } 
    catch(FacebookExceptionsFacebookResponseException $e) {}
    catch(FacebookExceptionsFacebookSDKException $e) {}
    $graphNode = $response->getGraphNode();
    if(!empty($graphNode)){
        $query = _que('SELECT * FROM customer where fbid = ?', [$graphNode['id']]);
        if(!is_array($query) || @!isset($query['failed'])){
            $pf_img=null;
            $image = file_get_contents($graphNode['picture']['url']);
            if ($image !== false){
                $pf_img = 'data:image/jpg;base64,'.base64_encode($image);
            }
            $_COOKIE['FBIMG']=$graphNode['picture']['url'];
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($user)){
                $data = ['msg' => 'Has to register','eval'=>"window.location.replace('?page=register&fbid={$graphNode['id']}&fn={$graphNode['first_name']}&ln={$graphNode['last_name']}')"];
            } else {
                if(!empty($pf_img)){
                    $query = _que('UPDATE customer SET pf_img = ? WHERE id=?',[$pf_img,$user['id']]);
                }
                require_once '../API/PHPMailer.php';
                require_once '../API/SMTP.php';
                require_once '../API/Exception.php';
                require_once '../API/OAuth.php';
                $ip = get_client_ip();
                $ip_data= unserialize(_req('http://ip-api.com/php/'.$ip));
                $country=($ip_data['status']=='success')?$ip_data['country']:'Unknown';
                $rg=($ip_data['status']=='success')?$ip_data['regionName']:'Unknown';
                $city=($ip_data['status']=='success')?$ip_data['city']:'Unknown';
                // $setting=[];
                // $query_stmp = _que('SELECT * FROM setting');
                // if(!is_array($query_stmp) || @!isset($query_stmp['failed'])){
                //     $setting = $query_stmp->fetchAll(PDO::FETCH_ASSOC);
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
                $mail->Subject = '[Craft.in.th] มีคนเข้าสู่ระบบด้วย Facebook';
				/*
                $res = '<html?><center style="@import url(https://fonts.googleapis.com/css2?family=Kanit&display=swap);font-family: \'Kanit\', sans-serif;">
                <div style="text-align:center;width:100%;max-width:500px; background: rgb(12,112,222);
                background: linear-gradient(90deg, rgba(12,112,222,1) 0%, rgba(47,77,189,1) 100%);
                padding:4rem;">
                <center>
                    <div style="color:#111;padding:2rem;background-color:#fff;width:100%;max-width:350px;">
                        <p>สวัสดี</p>
                        <p>เราได้ส่ง Email นี้มา เพราะ มีคนเข้าสู่ระบบโดย Facebook โดย</p>
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
            $email_text = ['user/login.png', $user['fname'].' '.$user['lname'], 'Login infomation', 'เราได้ส่ง Email นี้มาเพื่อแจ้งเตือนการเข้าสู่ระบบผ่าน Facebook โดย', 'IP '.$ip.'<br>Country '.$country.'<br>Region '.$rg.'<br>City '.$city.'<br>หากไม่ใช่คุณ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ', 'ติดต่อเรา', 'https://www.facebook.com/craftstudioofficial'];
            $res = str_replace(['{{image}}', '{{customer_full_name}}', '{{title}}', '{{subtitle}}', '{{description}}', '{{button_text}}', '{{button_url}}'], $email_text, file_get_contents(__DIR__.'/../components/email_template.html'));
				
                $res_alt = 'สวัสดี เราได้ส่ง Email นี้มา เพราะ มีคนเข้าสู่ระบบโดย Facebook โดย IP '.$ip.' Country '.$country.' Region '.$rg.' City '.$city.' หากไม่ กรุณาติดต่อเราโดยทันที เพื่อป้องกันมิจฉาชีพที่จะมาสวมรอยเป็นคุณ '.$end_body;
                
                $mail->addAddress($user['email'],$user['fname']);
                $mail->Body    = $res;
                $mail->AltBody = $res_alt;
                $mail->send();
                $_SESSION['username']=$user['id'];
				$_SESSION['2fa']=true;
                http_response_code(200);
                _que('INSERT INTO log (data) VALUES (?)',["Login to {$user['email']} via Facebook {$ip} {$country} {$rg} {$city}"]);

                $data = ['msg' => L::login.' '.L::complete,'eval'=>'window.location.replace("?page=home")'];
            }
        } else {
            $data = ['msg' =>$query['msg']];
        }
    }
}