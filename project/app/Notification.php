<?php

namespace App;

use JetBrains\PhpStorm\Pure;

class Notification implements \JsonSerializable
{
    public const FAILURE = false;
    public const SUCCESS = true;

    private array $_collection;
    private bool $_status;

    private string|array $_message;

    private function __construct(bool $type, array|string $message)
    {
        $this->_status = $type;
        $this->_collection = ['success' => $this->_status];
        if ($message) {
            $this->_message = $message;
            /** @noinspection AdditionOperationOnArraysInspection */
            $this->_collection += \is_array($message) ? $message : ['message' => $this->_message];
        }
    }

    #[Pure] public static function success($message = null): Notification
    {
        return new static(self::SUCCESS, $message);
    }

    #[Pure] public static function failure($message = null): Notification
    {
        return new static(self::FAILURE, $message);
    }

    /**
     * @example Notification::success('Ok')->with(['status' => $httpCode]);
     */
    public function with(array $data, bool $replace = false): self
    {
        if ($replace) {
            $this->_collection = array_merge($this->_collection, $data);
        } else {
            /** @noinspection AdditionOperationOnArraysInspection */
            $this->_collection += $data;
        }
        return $this;
    }

    public function isFailed(): bool
    {
        return $this->_status === self::FAILURE;
    }

    public function getMessage(): array|string
    {
        return $this->_message;
    }

    public function getCollection(): array
    {
        return $this->_collection;
    }

    #[Pure] public function jsonSerialize(): array
    {
        return $this->getCollection();
    }
}
