<?php

namespace App\Modules\Api\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ApiAuth
{
    public function __construct(public ?string $type = null) {}
}
