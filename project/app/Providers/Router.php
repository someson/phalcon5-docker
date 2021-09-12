<?php

namespace App\Providers;

use Phalcon\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Router as BaseRouter;
use Phalcon\Registry;

class Router implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'router';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {

            $router = new BaseRouter(false);
            $router->setDefaultModule('frontend');
            $router->setDefaultController('index');
            $router->setDefaultAction('index');
            $router->removeExtraSlashes(true);

            /** @var Registry $registry */
            /** @var Di $this */
            $registry = $this->get('registry');
            $modules = (array) $registry->offsetGet('modules');

            foreach ($modules as $module) {
                $router->mount(new $module['routes']);
            }
            $router->notFound([
                'controller' => 'error',
                'action' => 'notFound',
            ]);

            return $router;
        });
    }
}
