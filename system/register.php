<?php
if (empty($_SESSION['username']))
{
    require_once '../API/Validator.php';

    $_LANG = (!empty($_SESSION['lang']) && $_SESSION['lang'] == 'th') ? 'th' : 'en';
    $v = new Valitron\Validator($_P, [], $_LANG, 'Vaild_lang');

    $v->rule('required', ['first_name', 'last_name', 'email', 'password', 'confirm_password']);
    $v->rule('email', 'email');
    $v->rule('equals', 'confirm_password', 'password');
    $v->rule('regex', 'first_name', '/^[a-zA-Zก-ฮะ-์]{2,}$/');
    $v->rule('regex', 'last_name', '/^[a-zA-Zก-ฮะ-์]{2,}$/');
    $v->rule('accepted', 'accept');
    $v->rule('lengthMin', 'password', 6);

    $v->rule('numeric', ['fbid']);

    if ($v->validate())
    {
        if (!mail_check($_P['email']))
        {
            if (hcap_check($_P['h-captcha-response']))
            {
                $salt = _ranstr();
                $hash = hash('sha256', hash('sha256', $_P['password']) . $salt);
                $pass = htmlspecialchars(strip_tags("\$SHA\${$salt}\${$hash}"));
                $fbid = (!empty($_P['fbid'])) ? $_P['fbid'] : 'no_fbid';
                $query = _que('SELECT * FROM customer where email = ? OR fbid = ?', [$_P['email'], $fbid]);
                if (!is_array($query) || @!isset($query['failed']))
                {
                    $user = $query->fetch(PDO::FETCH_ASSOC);
                    if (empty($user) || $user['fbid'] == '')
                    {
                      $fbid = ($fbid == 'no_fbid') ? '' : $_P['fbid'];
                        $pf_img = '';
                        $image = file_get_contents($_COOKIE['FBIMG']);
                        if ($image !== false)
                        {
                            $pf_img = 'data:image/jpg;base64,' . base64_encode($image);
                        }
                        $query = _que('INSERT INTO customer (fname, lname, email, password, fbid, pf_img) VALUES (?,?,?,?,?,?)', [$_P['first_name'], $_P['last_name'], $_P['email'], $pass, $fbid, $pf_img]);
                        if (!is_array($query) || @!isset($query['failed']))
                        {
                            http_response_code(200);
                            $idx = $_PDOOO->lastInsertId();
                            $_SESSION['username'] = $idx;
                            $data = ['msg' => L::register . ' ' . L::complete, 'eval' => 'window.location.replace("?page=home")'];
                        }
                        else
                        {
                            $data = ['msg' => $query['msg']];
                        }
                    }
                    else
                    {
                        $data = ['msg' => L::email_exist];
                    }
                }
                else
                {
                    $data = ['msg' => $query['msg']];
                }
            }
            else
            {
                $data = ['msg' => 'Captcha ไม่ผ่าน'];

            }
        }
        else
        {

            $data = ['msg' => 'Mail อะไรวะเนี่ย (╯°□°）╯︵ ┻━┻'];
        }
    }
    else
    {
        $data = ['msg' => array_values($v->errors()) [0][0]];
    }
}
else
{
    $data = ['msg' => 'You are alreaddy login.'];
}

