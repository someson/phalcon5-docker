<?php
/** @var \Codeception\Module\Phalcon5 $this */

$root = dirname(__DIR__, 2);

require_once $root . '/app/Constants.php';
require_once $root . '/app/Functions.php';
require_once $root . '/vendor/autoload.php';

defined('BASE_DIR') || define('BASE_DIR', dirname(__DIR__, 2));
defined('FIXTURES') || define('FIXTURES', BASE_DIR . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '_fixtures');

(new \App\Env(dirname(__DIR__)))->load();

$moduleConfig = $this->_getConfig();
$domain = $moduleConfig['site'] ?? env('DEFAULT_DOMAIN');

$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'] ?? BASE_DIR;
$_SERVER['SERVER_NAME']   = $_SERVER['SERVER_NAME'] ?? $domain ?? env('DEFAULT_DOMAIN');

return new \Phalcon\Mvc\Application(new \Phalcon\Di\FactoryDefault());
