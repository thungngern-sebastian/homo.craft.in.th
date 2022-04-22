<?php
@require_once "ess.php";

require_once '../API/i18n.class.php';

$_P=formee($_POST);
$_G=formee($_GET);
// ห้าม แก้
http_response_code(400);
$data = ['msg' => 'Error has occured, Please contact administrator']; 
// ห้าม แก้

if(!empty($_G['a']) && ctype_alnum(str_replace(['-', '_'], '', $_G['a'])) && file_exists("../system/{$_G['a']}.php")){
    session_start();
    $i18n = new i18n();
    $i18n->setCachePath('../cache');
    $i18n->setFilePath('../lang/{LANGUAGE}.ini');
    $i18n->setFallbackLang('en');
    $i18n->setSectionSeparator('_');
    $i18n->setMergeFallback(true);
    $i18n->init();
    @require_once "system/{$_G['a']}.php";
}

header('Content-Type: application/json');
die(json_encode($data));