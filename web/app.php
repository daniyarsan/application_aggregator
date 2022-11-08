<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__ . '/../app/bootstrap.php.cache';

$deny = array("104.203.236.100");
if (in_array($_SERVER[ 'REMOTE_ADDR' ], $deny)) {
    header("location: https://example.com/");
    exit();
}

// Enable APC for autoloading to improve performance.
// You should change the ApcClassLoader first argument to a unique prefix
// in order to prevent cache key conflicts with other applications
// also using APC.
/*
$apcLoader = new ApcClassLoader(sha1(__FILE__), $loader);
$loader->unregister();
$apcLoader->register(true);
*/
date_default_timezone_set("America/New_York");
require_once __DIR__ . '/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

if ($_SERVER['REMOTE_ADDR'] == '158.181.29.13') {
    $kernel = new AppKernel('dev', false);
} else {
    $kernel = new AppKernel('prod', false);
}
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
