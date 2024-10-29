<?php

namespace App\Shared;

final class ExceptionDto
{
    public function __construct(
        public readonly string $className,
        public readonly string|array $message,
    ) {}
}
