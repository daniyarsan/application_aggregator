<?php

namespace Appform\FrontendBundle\Extensions;

class Util
{
    public static function jasonpWrapper(array $data, $callback)
    {
        return $callback . '(' . json_encode($data) . ')';
    }

    public static function getIP()
    {
        if (isset($_SERVER[ 'HTTP_X_FORWARDED_FOR' ])) $ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        elseif (isset($_SERVER[ 'REMOTE_ADDR' ])) $ip = $_SERVER[ 'REMOTE_ADDR' ];
        else $ip = "0";
        return $ip;
    }

    public function captchaVerify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"=>"6LfhV4gUAAAAAHWwdP91m0uM9F4HMZ1HYHitg2My","response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }

}