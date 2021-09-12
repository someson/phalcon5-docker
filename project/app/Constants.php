<?php

const DS = DIRECTORY_SEPARATOR;

defined('BASE_DIR')   || define('BASE_DIR', dirname(__DIR__));
defined('APP_DIR')    || define('APP_DIR',    BASE_DIR . DS . 'app');     // app
defined('MODULE_DIR') || define('MODULE_DIR', APP_DIR  . DS . 'Modules'); // app/Modules
defined('SHARED_DIR') || define('SHARED_DIR', APP_DIR  . DS . 'Shared');  // app/Shared

defined('VENDOR_DIR') || define('VENDOR_DIR', BASE_DIR . DS . 'vendor');  // vendor
defined('PUBLIC_DIR') || define('PUBLIC_DIR', BASE_DIR . DS . 'public');  // public
defined('TMP_DIR')    || define('TMP_DIR',    BASE_DIR . DS . 'storage'); // storage
defined('CACHE_DIR')  || define('CACHE_DIR',  TMP_DIR  . DS . 'cache');   // storage/cache

defined('URI') || define('URI', rtrim($_SERVER['REQUEST_URI'], '/ '));
