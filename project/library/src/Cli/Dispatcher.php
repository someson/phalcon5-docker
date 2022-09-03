<?php

namespace Library\Cli;

use Phalcon\Cli\Dispatcher as BaseDispatcher;
use Phalcon\Support\Collection;

class Dispatcher extends BaseDispatcher
{
    protected Collection $_userOptions;

    public function __construct()
    {
        $this->_userOptions = new Collection();
    }

    public function getUserOptions(): Collection
    {
        return $this->_userOptions;
    }
}
