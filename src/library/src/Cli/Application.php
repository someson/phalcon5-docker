<?php

namespace Library\Cli;

use Phalcon\Cli\Console as BaseApplication;

class Application extends BaseApplication
{
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
