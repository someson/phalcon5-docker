<?php

namespace Library\Cli\Listeners;

use Library\Cli\Application as Console;
use Library\Cli\CommandOptions\{ Recordable, SingleInstance, Verbose };
use Library\Cli\Handler;
use Phalcon\Config\Config;
use Phalcon\Events\Event;
use RuntimeException;

class TaskListener
{
    protected Handler $_cliHandler;

    /**
     * @param Config|null $cliConfig
     */
    public function __construct(?Config $cliConfig = null)
    {
        $this->_cliHandler = new Handler();
        if ($cliConfig) {
            $this->_cliHandler->setConfig($cliConfig);
        }
    }

    public function getHandler(): Handler
    {
        return $this->_cliHandler;
    }

    /**
     * @param Event $event
     * @param Console $console
     * @return bool
     */
    public function boot(Event $event, Console $console): bool
    {
        $args = $console->getArguments();
        $handler = $this->getHandler();

        $handler->setTask($args['task']);
        $handler->setAction($args['action']);
        $handler->setOptions($console->getOptions());

        $handler->registerOption(new SingleInstance('s')); // -s = run only one allowed instance
        $handler->registerOption(new Recordable('r'));     // -r = recording
        $handler->registerOption(new Verbose('v'));        // -v = verbose info

        return ! $event->isStopped();
    }

    /**
     * @param Event $event
     * @return bool
     * @throws RuntimeException
     */
    public function beforeHandleTask(Event $event): bool
    {
        $this->getHandler()->startTask();
        return ! $event->isStopped();
    }

    public function afterHandleTask(): void
    {
        $this->getHandler()->finishTask();
    }
}
