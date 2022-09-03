<?php

namespace App\Providers;

use Phalcon\Cli\Router;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class RouterCli implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'router';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            $router = new Router();
            $router->setDefaultModule('cli');

            return $router;
        });
    }
}
