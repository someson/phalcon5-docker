<?php
/** @var \Codeception\Module\Phalcon5 $this */

$root = dirname(__DIR__, 3);

if (! function_exists('env')) {
    function env($name, $default = null) {
        return \App\Env::get($name, $default);
    }
}

defined('LIB_DIR')  || define('LIB_DIR',  $root . DIRECTORY_SEPARATOR . 'library');
defined('LIB_FIXTURES') || define('LIB_FIXTURES', LIB_DIR . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '_fixtures');

require_once $root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

(new \App\Env(dirname(__DIR__)))->load();

$di = new \Phalcon\Di\FactoryDefault\Cli();
\Phalcon\Di\Di::setDefault($di);

return new \Library\Cli\Application($di);
