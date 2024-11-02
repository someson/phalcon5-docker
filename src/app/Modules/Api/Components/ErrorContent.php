<?php

namespace App\Modules\Api\Components;

use App\Shared\Http\Status;

class ErrorContent implements \JsonSerializable
{
    /** @var array<string, mixed> */
    protected array $_schema = [];

    public function __construct(
        private int $statusCode,
        private readonly ?string $message = null
    ) {
        $httpStatus = Status::tryFrom($this->statusCode);
        if (! $httpStatus) {
            $this->statusCode = Status::INTERNAL_SERVER_ERROR->value;
            $httpStatus = Status::tryFrom($this->statusCode);
        }
        $this->_schema = [
            'code' => $this->statusCode,
            'message' => $this->message ?? $httpStatus->message(),
        ];
    }

    public function setParam(string $key, mixed $message): self
    {
        $this->_schema[$key] = $message;
        return $this;
    }

    public function setStatusMessage(string $message): self
    {
        return $this->setParam('message', $message);
    }

    public function setDebugMessage(string $message): self
    {
        return $this->setParam('debug', $message);
    }

    public function get(): array
    {
        return $this->_schema;
    }

    public function jsonSerialize(): array
    {
        return $this->get();
    }
}
