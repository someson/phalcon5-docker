<?php

namespace App\Modules\Api\Exceptions;

interface CollectableInterface
{
    public function getMessages(): array;
}
