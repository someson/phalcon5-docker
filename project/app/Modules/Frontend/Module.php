<?php

namespace App\Modules\Frontend;

use App\Dispatcher;
use App\Shared\Listeners\ErrorListener;
use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public const SESSION_PREFIX = 'F_';

    public function registerAutoloaders(DiInterface $container = null): void
    {
    }

    public function registerServices(DiInterface $container): void
    {
        /** @var Manager $eventsManager */
        $eventsManager = $container->getShared('eventsManager');
        $eventsManager->attach('view', new Listeners\ViewListener());

        $container->setShared('dispatcher', function() use ($eventsManager) {
            /** @var Di $this */
            $eventsManager->attach('dispatch', new ErrorListener());

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\\Controllers');
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });

        $container['session']->setOptions(['uniqueId' => self::SESSION_PREFIX]);
        $container->getService('session')->resolve();

        $container['view']->addViewsDir([__DIR__.'/Views/', SHARED_DIR.'/Views/']);
        $container['view']->setEventsManager($eventsManager);
        $container['view']->setVar('site', $container['config']->get('app', []));

        $container['router']->notFound(['controller' => 'error', 'action' => 'notFound']);
    }
}
