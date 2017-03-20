<?php

namespace Appform\FrontendBundle\Extensions;


class Util {

	public static function jasonpWrapper(array $data, $callback)
	{
		return $callback.'(' . json_encode($data) . ')';
	}
	function CountVisitors() {
		global $dbfile, $expire;
		$cur_ip = getIP();
		$cur_time = time();
		$dbary_new = array();

		$dbary = unserialize(file_get_contents($dbfile));
		if(is_array($dbary)) {
			while(list($user_ip, $user_time) = each($dbary)) {
				if(($user_ip != $cur_ip) && (($user_time + $expire) > $cur_time)) {
					$dbary_new[$user_ip] = $user_time;
				}
			}
		}
		$dbary_new[$cur_ip] = $cur_time; // add record for current user

		$fp = fopen($dbfile, "w");
		fputs($fp, serialize($dbary_new));
		fclose($fp);

		$out = sprintf("%03d", count($dbary_new)); // format the result to display 3 digits with leading 0's
		return $out;
	}

	function getIP() {
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		elseif(isset($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
		else $ip = "0";
		return $ip;
	}

}