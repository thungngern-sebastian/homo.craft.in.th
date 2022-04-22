<?php
require_once __DIR__.'/../../ess.php';
require_once __DIR__.'/../../API/Xen.php';

$suspend = [];
$data = [];
$host = [];
$customer = [];
$ip = [];

$query = _que('SELECT * FROM `hosts` WHERE `host` NOT IN ("43.228.86.10")');
if($query) {
    $host = $query->fetchAll();
} else {
    die('[X] Cant fetch Host!');
}

$query = _que('SELECT `id`, `email`, `point` FROM `customer`');
if($query) {
    $customer = $query->fetchAll(PDO::FETCH_UNIQUE);
} else {
    die('[X] Cant fetch Customer!');
}

$query = _que('SELECT `uuid`, `ipv4` FROM `ip_address`');
if($query) {
    $ip = $query->fetchAll(PDO::FETCH_UNIQUE);
} else {
    die('[X] Cant fetch IP!');
}

foreach ($host as $h) {
    echo '[!] Connecting '.$h['host'].PHP_EOL;
    try {
        $xen = new PsXenAPI($h['host'], $h['username'], $h['password']);
    } catch (\Throwable $th) {
        echo '[x] Cant connect '.$h['host'].PHP_EOL;
        die('Pls enable XMLRpc');
    }
    if(is_null($xen->id_session)) {
        echo '[x] Cant connect '.$h['host'].PHP_EOL;
        continue;
    }
    
    echo '[/] Connected '.$h['host'].PHP_EOL;
    $query = _que('SELECT * FROM `vm` WHERE `host` = ?', [$h['host']]);
    if($query) {
        $data = $query->fetchAll();
        echo '[/] GET VM HOST '.$h['host'].PHP_EOL;
    } else {
        echo '[X] GET VM HOST ERROR '.$h['host'].PHP_EOL;
        continue;
    }
    foreach ($data as $x) {
        $opq_ref = $xen::apref($x['ref']);

        //Prefix
        $name = '';
        if(empty($ip[$x['ref']])) {
            $name .= 'Unknown';
        } else {
            $name .= $ip[$x['ref']]['ipv4'];
        }
        $name .= ' - ';
        if(empty($customer[$x['cusid']]['email'])) {
            $name .= 'Unknown';
        } else {
            $name .= $customer[$x['cusid']]['email'];
        }
        $name .= ' - ';
        $name .= ($x['unlimited'] == 1) ? 'UNLIMIT' : $x['timestamp'];
        echo '[/] Rename '.$name.PHP_EOL;
        $s = $xen->rq('VM.set_name_label', [$opq_ref, $name]);
    }
}
echo '[/] Done';
