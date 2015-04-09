<?php

namespace Appform\FrontendBundle\Extensions;


class Util {

	public static function jasonpWrapper(array $data, $callback)
	{
		return $callback.'(' . json_encode($data) . ')';
	}
}