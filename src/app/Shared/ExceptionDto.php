<?php

namespace App\Shared;

final readonly class ExceptionDto implements \JsonSerializable
{
    public function __construct(
        public string $className,
        public string|array $message,
    ) {}

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
