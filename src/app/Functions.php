<?php

if (! function_exists('env')) {
    function env($name, $default = null) {
        return \App\Env::get($name, $default);
    }
}
