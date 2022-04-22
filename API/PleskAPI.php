<?php
/**
 * @category   PleskAPI-PHP
 * @package    PleskAPI-PHP
 * @author     Yoswaris Lawpaiboon <me@kiznick.in.th>
 * @copyright  Copyright (c) 2021 Yoswaris Lawpaiboon.
 * @license    https://creativecommons.org/licenses/by/4.0/ Attribution 4.0 International (CC BY NC SA 4.0)
 * @version    1.0.0
 * @deprecated Plesk json library for php.
**/

class kiznick_Plesk_API {
    public $host = null;
    public $user = null;
    public $pass = null;

    public function __construct($host, $user, $pass){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function request($method, $path, $params = []) {
        if(is_null($path) || is_null($method) || is_null($params)) {
            return false;
        }
        $url = $this->host.'api/v2/'.$path;
        $curl = curl_init();
        if(!in_array($method, ['POST', 'PUT', 'DELETE', 'GET'])) {
            return false;
        }
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if($method == 'GET') {
            $url = sprintf("%s?%s", $url, http_build_query($params));
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $this->user.":".$this->pass); 
        $curl_result = curl_exec($curl);
        $result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(!$curl_result) {
            $result['body'] = $curl_result;
        } else {
            $result['body'] = json_decode($curl_result, true);
        }
        curl_close($curl);
        return $result;
    }
}