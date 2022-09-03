<?php

namespace Library\Cli;

use Library\Cli\Interfaces\CommandOption;
use Phalcon\Config\Config;

abstract class AbstractOption implements CommandOption
{
    protected string $_command;
    protected bool $_enabled;
    protected array $_config;

    public function __construct(string $command)
    {
        $this->_command = $command;
        $this->_enabled = false;
        $this->_config = [];

        $this->initialize();
    }

    public function getCommand(): string
    {
        return $this->_command;
    }

    public function enable(): void
    {
        if (! $this->_enabled) {
            $this->_enabled = true;
        }
    }

    public function isEnabled(): bool
    {
        return $this->_enabled;
    }

    public function setConfig(array $config): void
    {
        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * @param string $path a.b.c
     * @param mixed $value
     * @return void
     */
    public function setConfigValue(string $path, $value): void
    {
        $param = (new Config($this->_config))->path($path, null);
        if (! is_null($param)) {
            $config = &$this->_config;
            $params = explode('.', $path);
            while (count($params)) {
                $config = &$config[array_shift($params)];
            }
            $config = $value;
        }
    }

    public function getConfig(): array
    {
        return $this->_config;
    }

    public function isOptionEnabled(string $option): bool
    {
        return isset($this->_config['runtime']['options'][$option]) && $this->_config['runtime']['options'][$option];
    }

    abstract public function initialize(): void;
    abstract public function onStart(): void;
    abstract public function onFinish(): void;
}
