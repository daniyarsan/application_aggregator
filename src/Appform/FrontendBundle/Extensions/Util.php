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

    public static function getReferrer() {

    }
}