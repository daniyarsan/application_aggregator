<?php

namespace Appform\FrontendBundle\Extensions;

use Appform\FrontendBundle\Entity\Applicant;

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

    public function sendPixelRequest($url, $headers)
    {
        $i = 0;
        $pixelArray = array("https://track.ziprecruiter.com/conversion?enc_account_id=0fc30330");
        $count = sizeof($pixelArray);

        while ($i < $count) {
            $line = $pixelArray[ $i ];
            //echo "<br/> line = ".$line;
            // replace [afid], [sid], [c1], [c2], [c3], or [t] with the corresponding value in $_SESSION
            $line = preg_replace_callback(
                '/\[(afid|sid|c1|c2|c3|t)\]/',
                function ($matches) {
                    return $_SESSION[ $matches[ 1 ] ];
                },
                $line
            );
            //echo "<br/>".$i.": ".$line;
            $this->firePixels($line, $headers);
            $i++;
        }
        //echo "Done";
    }

    public function firePixels($url, $headers)
    {
        $cookie = str_replace('Cookie: ', '', $headers[0]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, "https://app.healthcaretravelers.com/form/ZR?utm_source=nurse");

        //curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');

        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        var_dump($head);
        exit;

        if (!$head) {
            die('Pixel Execution Failed: ' . $error . " | " . $url);
            return false;
        }
        if ($httpCode === 200) {
            return true;
        } else {
            return false;
        }
    }
}