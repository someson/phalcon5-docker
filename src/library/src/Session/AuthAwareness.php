<?php

namespace Library\Session;

class AuthAwareness
{
    protected string $_index;
    protected \ArrayObject $_collection;

    public function __construct(string $scope, string $identityName)
    {
        $this->_index = sprintf('%s#%s', $scope, $identityName);
    }

    public function getCollection(): \ArrayObject
    {
        if (! $this->_collection) {
            $this->_collection = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
            if (isset($_SESSION[$this->_index]) && \is_array($_SESSION[$this->_index])) {
                $this->_collection->exchangeArray($_SESSION[$this->_index]);
            }
        }
        return $this->_collection;
    }

    public function exists(): bool
    {
        return $this->getCollection()->count() > 0;
    }
}
