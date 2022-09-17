<?php

namespace Library\Cli;

use Library\Cli\Interfaces\Reportable;
use Library\Cli\Traits\Configurable;

abstract class AbstractAdapter implements Reportable
{
    use Configurable;

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return static::class;
    }
}
