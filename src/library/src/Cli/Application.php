<?php

namespace Library\Cli;

use Phalcon\Cli\Console as BaseApplication;

#[\AllowDynamicProperties]
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
