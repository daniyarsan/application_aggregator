<?php

namespace Appform\BackendBundle\Twig;

class AppExtension extends \Twig_Extension
{
	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
			new \Twig_SimpleFilter('findCountry', array($this, 'findCountry')),
			new \Twig_SimpleFilter('unserialize', 'unserialize'),
			new \Twig_SimpleFilter('var_dump', 'var_dump')

		);
	}

	public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
	{
		$price = number_format($number, $decimals, $decPoint, $thousandsSep);
		$price = '$'.$price;

		return $price;
	}

	public function findCountry($ip)
	{
		$details = json_decode(file_get_contents("http://ipinfo.io/" . $ip));
		return $details;
	}

	public function getName()
	{
		return 'app_extension';
	}
}