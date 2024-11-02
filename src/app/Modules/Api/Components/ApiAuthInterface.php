<?php

namespace App\Modules\Api\Components;

use Phalcon\Http\Request;

interface ApiAuthInterface
{
    public function from(Request $request): static;
    public function getToken(): string;
}
