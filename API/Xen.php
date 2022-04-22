<?php
/**
* [ MODIFIED ] PsXenAPI Class
 * @category  Xen Lib
 * @package   ps-xenapi
 * @author    PusanStudio <me@pusanstudio.com>
 * @copyright Copyright (c) 2020-2022
 * @license   https://creativecommons.org/licenses/by/4.0/ Attribution 4.0 International (CC BY SA 4.0)
 * @link      https://pusanstudio.com
 * @version   1.2.0
**/

class PsXenAPI {
    public $host = null;
    public $user = null;
    public $pass = null;
    public $id_session = null;
    public function __construct($host,$user,$pass){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->login();
    }
    public function login(){
        if (is_null($this->host) || is_null($this->user) || is_null($this->pass)) {
            return false;
        }
        else{
            $r = $this->xen_request($this->host, 'session.login_with_password', array($this->user, $this->pass));
            if (@$r['ErrorDescription'][0] != "HOST_IS_SLAVE") {
                if (is_array($r) && $r['Status'] == 'Success') {
                    $this->id_session = $r['Value'];
                    return true;
                } else {
                    return false;
                }
            }else {
                return false;
            }
        }
    }
    private function xen_request($url, $name, $params){
        $data = xmlrpc_encode_request($name, $params);
        $headers = array('Content-type: text/xml', 'Content-length: ' . strlen($data));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, -0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($ch);
        curl_close($ch);
        $ret = xmlrpc_decode($resp);
        return $ret;
    }
    
    public function rq($name, $params = []){
        array_unshift($params, $this->id_session);
        $r = $this->xen_request($this->host, $name, $params);
        return $r;
    }

    public function B2S($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    public function S2B($size, $power  = 3)
    {
        $data = $size*pow(1024, $power);
        return $data;
    }

    public function SEC2DATE($seconds) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days %h hours %i minutes %s seconds');
    }  

    public function cutref($str){
        return substr(strstr($str, 'OpaqueRef:'), 10);
    }
    public function apref($str){
        return 'OpaqueRef:'.$str;
    }
}

