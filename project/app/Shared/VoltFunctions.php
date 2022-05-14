<?php

namespace App\Shared;

class VoltFunctions
{
    public function compileFunction($name, $arguments)
    {
        if (function_exists($name)) {
            return $name . '(' . $arguments . ')';
        }
    }
}
