<?php

namespace Library\Cli\Interfaces;

interface CommandOption
{
    public function getCommand(): string;
    public function enable(): void;
}
