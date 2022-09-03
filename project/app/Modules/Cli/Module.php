<?php

namespace App\Modules\Cli;

use Library\Cli\Dispatcher;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null): void {}

    public function registerServices(DiInterface $container): void
    {
        $container->set('dispatcher', function() {
            /** @var Manager $eventsManager */
            /** @var Di $this */
            $eventsManager = $this->getShared('eventsManager');
            $eventsManager->attach('dispatch', new Listeners\ErrorListener());

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\\Tasks');
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });
    }
}
