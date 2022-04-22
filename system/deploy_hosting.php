<?php
if (!empty($_P['plan']) && is_numeric($_P['plan']) && !empty($_P['length']) && is_numeric($_P['length']) && in_array($_P['length'], [1, 30, 365]) && !empty($_P['domain']) && !empty($_SESSION["username"])) {
    $_P['domain'] = strtolower($_P['domain']);
    if(preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/', $_P['domain'])) {
        $pdo = _conn();
        $query = _que('SELECT * FROM `customer` WHERE `id` = ?', [$_SESSION["username"]]);
        if (!is_array($query) || @!isset($query['failed'])) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if (empty($user)) {
                unset($_SESSION["username"]);
            } elseif (empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
                $is_admin = ($user['admin'] == 1 && !empty($user['admin'])) ? true : false;
                $is_activated = ($user['is_activated'] == 1 && !empty($user['is_activated'])) ? true : false;
                $is_suspended = ($user['suspended'] == 1 && !empty($user['suspended'])) ? true : false;
    
                if (!$is_suspended && $is_activated) {
                    $query = _que('SELECT * FROM `hosting_plans` WHERE `id` = ?', [$_P['plan']]);
                    if(!is_array($query) || @!isset($query['failed'])){
                        $plan = $query->fetch(PDO::FETCH_ASSOC);
                        $length = [
                            1 => 'daily',
                            30 => 'monthly',
                            365 => 'yearly'
                        ];
                        $price = $plan[$length[$_P['length']]];
                        if($user['point'] >= $price) {
                            function RandomString($length = 16, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
                                $charactersLength = strlen($characters);
                                $randomString = '';
                                for ($i = 0; $i < $length; $i++) {
                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                }
                                return $randomString;
                            }

                            require_once ('../API/PleskAPI.php');
                            $plesk = new kiznick_Plesk_API('https://hosting.drite.in.th:8443/', 'root', 'limited3AB@@#');
                            $domain = $plesk->request('GET', 'domains', [
                                'name' => $_P['domain']
                            ]);
                            if(empty($domain['body'])) {
                                $username = RandomString(8, 'abcdefghijklmnopqrstuvwxyz');
                                $password = RandomString(12).rand(0, 9).RandomString(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ').RandomString(1, 'abcdefghijklmnopqrstuvwxyz').RandomString(1, '@#!$%*');
                                $customer = $plesk->request('POST', 'clients', [
                                    'name' => $user['fname'].' '.$user['lname'],
                                    'email' => $user['email'],
                                    'login' => $username,
                                    'password' => $password,
                                    'type' => 'customer',
                                    'description' => 'UID : '.$user['id'].', Domain : '.$_P['domain']
                                ]);
                                if($customer['code'] == 201) {
                                    $subscription = $plesk->request('POST', 'cli/subscription/call', [
                                        'params' => [
                                            "--create",
                                            $_P['domain'],
                                            "-owner",
                                            $username,
                                            "-service-plan",
                                            $plan['plesk_plan'],
                                            "-ip",
                                            "43.229.151.97",
                                            "-login",
                                            $username,
                                            "-passwd",
                                            $password
                                        ]
                                    ]);
                                    if($subscription['code'] == 200) {
                                        if($subscription['body']['code'] == 0) {
                                            $order_ref = hash('sha256', (microtime() . rand()) . $_SESSION["username"] . $_P['domain']);
                                            $info = 'Hosting Plan '.$_P['plan'].' '.$_P['length'].' Day';    
											
                                            _que('INSERT INTO `hosting` (`def_domain`, `base_price`, `expiration_date`, `duration`, `disk`, `traffic`, `domain`, `email`, `dbcount`, `username`, `password`, `cusid`, `autorenew`, `cid`) VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);', [
                                                $_P['domain'], $price, date('Y-m-d H:i:s', time() + ($_P['length'] * 86400)), $_P['length'], $plan['disk'], $plan['traffic'], $plan['domain'], $plan['email'], $plan['dbcount'], $username, $password, $user['id'], 1, $customer['body']['id']
                                            ]);
    
                                            _que('UPDATE `customer` SET `point` = `point` - ? WHERE `id` = ?',[$price, $user["id"]]);
    
                                            _que('INSERT INTO `order_history` (`ref`, `cusid`, `price`, `info`) values (?, ?, ?, ?);', [
                                                $order_ref, $user["id"], $price, $info
                                            ]);
    
                                            http_response_code(200);
                                            $data = ['msg' => 'สร้าง Hosting สำเร็จ', 'url' => '?page=home'];
                                        } else {
                                            $data = ['msg' => 'ไม่สามารถสร้าง Hosting ได้ในขณะนี้กรุณาแจ้งผู้ดูแลระบบ #3', 'data' => $subscription, 'user' => ['username' => $username, 'password' => $password, 'domain' => $_P['domain']]];
                                        }
                                    } else {
                                        $data = ['msg' => 'ไม่สามารถสร้าง Hosting ได้ในขณะนี้กรุณาลองอีกครั้งในภายหลัง #2'];
                                    }
                                } else {
                                    $data = ['msg' => 'ไม่สามารถสร้าง Hosting ได้ในขณะนี้กรุณาลองอีกครั้งในภายหลัง #1'];
                                }
                            } else {
                                $data = ['msg' => 'ไม่สามารถใช้โดเมนนี้ได้'];
                            }
                        } else {
                            $data = ['msg' => "Your account balance is insufficient."];
                        }
                    } else {
                        $data = ['msg' => 'ไม่พบแพลนที่คุณเลือกในระบบ'];
                    }
                } else {
                    $data = ['msg' => "This account doesn't met requirement."];
                }
            } else {
                $data = ['msg' => "This account doesn't met requirement."];
            }
        } else {
            $data = ['msg' => 'ระบบไม่สามารถใช้งานได้ในขณะนี้'];
        }
    } else {
        $data = ['msg' => 'Invalid Domain.'];
    }
} else {
    $data = ['msg' => 'Parameter missing'];
}

