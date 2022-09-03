<?php

namespace Library\Cli;

use OverflowException;
use Phalcon\Config\Config;

class Handler
{
    /** @var array<string, ?Interfaces\CommandOption> */
    protected array $_options = [];

    protected string $_action;
    protected string $_task;
    protected Config $_config;

    public function __construct()
    {
        $this->_config = new Config();
    }

    public function setTask(string $taskName): self
    {
        $this->_task = $taskName;
        return $this;
    }

    public function setAction(string $actionName): self
    {
        $this->_action = $actionName;
        return $this;
    }

    public function setOptions(array $options): void
    {
        foreach ($options as $option => $enabled) {
            $this->_options[$option] = null;
        }
    }

    public function startTask(): bool
    {
        foreach ($this->_options as $optionHandler) {
            if (is_object($optionHandler) && $optionHandler->isEnabled()) {
                $optionHandler->onStart();
            }
        }
        return true;
    }

    public function finishTask(): bool
    {
        foreach ($this->_options as $optionHandler) {
            if (is_object($optionHandler) && $optionHandler->isEnabled()) {
                $optionHandler->onFinish();
            }
        }
        return true;
    }

    public function registerOption(Interfaces\CommandOption $commandOption): void
    {
        $command = $commandOption->getCommand();
        if (! array_key_exists($command, $this->_options)) {
            return;
        }
        if (isset($this->_options[$command])) {
            $object = $this->_options[$command];
            throw new OverflowException(sprintf('Option [%s] already defined by [%s]', $command, get_class($object)));
        }
        $commandOption->setConfig(array_merge($this->_config->toArray(), [
            'runtime' => [
                'task' => $this->_task,
                'action' => $this->_action,
                'options' => array_map(static function() { return false; }, $this->_options),
            ],
        ]));
        $commandOption->enable();
        $this->_options[$command] = $commandOption;

        $enabledOptions = [];
        /**
         * @var string $o
         * @var AbstractOption $handler
         */
        foreach ($this->_options as $o => $handler) {
            if (is_object($handler) && $handler->isEnabled()) {
                $enabledOptions[] = $o;
            }
        }
        foreach ($this->_options as $handler) {
            if (is_object($handler) && $handler->isEnabled()) {
                foreach ($enabledOptions as $option) {
                    $handler->setConfigValue(sprintf('runtime.options.%s', $option), true);
                }
            }
        }
    }

    public function hasOption(string $command): bool
    {
        return isset($this->_options[$command]);
    }

    public function isOptionEnabled(string $command): bool
    {
        if ($handler = $this->getOptionHandler($command)) {
            return $handler->isEnabled();
        }
        return false;
    }

    public function getOptionHandler(string $command): ?Interfaces\CommandOption
    {
        return $this->_options[$command] ?? null;
    }

    public function setConfig(Config $config): self
    {
        $this->_config = $config;
        return $this;
    }
}
