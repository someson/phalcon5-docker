<?php

namespace App\Modules\Api\Components;

use Phalcon\Http\Request;

class ApiAuthFactory
{
    public static function create(string $type = 'bearer'): ApiAuthInterface
    {
        $className = match (strtolower($type))
        {
            // ...
            default => Bearer::class,
        };
        return new $className;
    }
}
