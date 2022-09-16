<?php

namespace Library\Cli\Traits;

trait Configurable
{
    protected array $_o = [];

    public function addOption(string $key, $value, bool $override = true): self
    {
        $exists = array_key_exists($key, $this->_o);
        if (! $exists || $override) {
            $this->_o[$key] = $value;
        }
        return $this;
    }

    public function hasOption(string $key): bool
    {
        return isset($this->_o[$key]);
    }

    public function getOption(string $key)
    {
        return $this->_o[$key] ?? null;
    }

    public function getOptions(): array
    {
        return $this->_o;
    }
}
