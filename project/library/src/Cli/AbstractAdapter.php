<?php

namespace Library\Cli;

abstract class AbstractAdapter implements Interfaces\Reportable
{
    use Traits\Configurable;

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return static::class;
    }
}
