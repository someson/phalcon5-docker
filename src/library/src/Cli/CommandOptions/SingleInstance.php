<?php

namespace Library\Cli\CommandOptions;

use Library\Cli\{ AbstractOption, Output, Pid };
use RuntimeException;

final class SingleInstance extends AbstractOption
{
    protected Pid $_pid;

    public function initialize(): void
    {
    }

    public function onStart(): void
    {
        $fileName = sprintf('%s-%s.pid', $this->_config['runtime']['task'], $this->_config['runtime']['action']);
        $this->_pid = new Pid(TMP_DIR . DS . 'console' . DS . $fileName);
        if ($this->_pid->exists()) {
            throw new RuntimeException('Instance of task is already running.', 999);
        }
        if (! $this->_pid->create()) {
            throw new RuntimeException('Unable to create pid file.');
        }
        if ($this->isOptionEnabled('v')) {
            Output::debug(PHP_EOL . sprintf('[DEBUG] Created Pid File: %s', $this->getPidFile()));
        }
    }

    public function onFinish(): void
    {
        $errorPrefix = Output::COLOR_RED . '[ERROR]' . Output::COLOR_NONE;
        if ($this->_pid->created() && ! $this->_pid->removed()) {
            $result = $this->_pid->remove();
            if ($this->isOptionEnabled('v')) {
                $result ?
                    Output::debug(sprintf(PHP_EOL . '[DEBUG] Removed Pid File: %s', $this->getPidFile()) . PHP_EOL) :
                    Output::error(sprintf('%s Failed to remove Pid File: %s', $errorPrefix, $this->getPidFile()));
            }
        } else {
            Output::error(sprintf('%s Failed to remove Pid file: File not found.', $errorPrefix));
        }
    }

    public function getPidFile(): string
    {
        return $this->_pid->getFileName();
    }
}
