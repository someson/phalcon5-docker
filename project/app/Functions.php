<?php

if (! function_exists('env')) {
    function env($name, $default = null) {
        return \App\Env::get($name, $default);
    }
}

if (! function_exists('__')) {
    function __($key = null, array $params = []): string {
        return $key ? \App\Translator::instance()->translate($key, $params) : '[TRANSLATION FAILED]';
    }
}
