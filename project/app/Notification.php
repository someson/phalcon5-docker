<?php

namespace App;

class Notification implements \JsonSerializable
{
    public const FAILURE = false;
    public const SUCCESS = true;

    private array $_collection;
    private bool $_status;

    /** @var array|string */
    private $_message;

    /**
     * @param bool $type
     * @param array|string $message
     */
    private function __construct(bool $type, $message)
    {
        $this->_status = $type;
        $this->_collection = ['success' => $this->_status];
        if ($message) {
            $this->_message = $message;
            $this->_collection += \is_array($message) ? $message : ['message' => $this->_message];
        }
    }

    /**
     * @param null $message
     * @return static
     */
    public static function success($message = null): Notification
    {
        return new static(self::SUCCESS, $message);
    }

    /**
     * @param null $message
     * @return static
     */
    public static function failure($message = null): Notification
    {
        return new static(self::FAILURE, $message);
    }

    /**
     * @param array $data
     * @param bool|false $replace
     * @return $this
     * @example Notification::success('Ok')->with(['status' => $httpCode]);
     */
    public function with(array $data, bool $replace = false): self
    {
        if ($replace) {
            $this->_collection = array_merge($this->_collection, $data);
        } else {
            $this->_collection += $data;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->_status === self::FAILURE;
    }

    /**
     * @return array|string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->_collection;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->getCollection();
    }
}
