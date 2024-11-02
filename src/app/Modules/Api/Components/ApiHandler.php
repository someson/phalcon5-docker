<?php

namespace App\Modules\Api\Components;

class ApiHandler
{
    public function __construct(private ?ApiAuthInterface $tokenHandler = null)
    {
        // ...
    }
}
