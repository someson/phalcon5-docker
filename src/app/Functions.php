<?php

if (! function_exists('env')) {
    function env($name, $default = null) {
        return \App\Env::get($name, $default);
    }
}

if (! function_exists('__')) {
    function __(string $key, array $params = [], bool $pluralize = true): string {
        return $key ? \Phalcon\I18n\Translator::instance()->_($key, $params, $pluralize) : '[TRANSLATION ERROR]';
    }
}
