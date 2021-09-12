<?php

set_time_limit(0);
error_reporting(E_ALL);

$dir = dirname(__DIR__);

require_once $dir . '/app/Constants.php';
require_once $dir . '/app/Functions.php';
require_once $dir . '/vendor/autoload.php';

try {

    (new \App\Env($dir))->load();
    $app = new \App\Bootstrap();
    $app->getApplication()->handle(URI);

} catch (\Throwable $e) {
    $filePath = explode(DS, $e->getFile());
    $isModule = end($filePath) === 'Module.php';
    if (! isset($app) || $isModule) {
        require_once $dir . '/app/Micro.php';
        exit();
    }
    $app->getApplication()->handleException($e);
}
