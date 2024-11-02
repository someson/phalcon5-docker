<?php

namespace App\Modules\Api;

use App\Shared\Dispatcher;
use Phalcon\Di\{ Di, DiInterface };
use Phalcon\Events\Manager;
use Phalcon\Http\Message\RequestMethodInterface as Http;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Router;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $container = null): void {}

    public function registerServices(DiInterface $container): void
    {
        $container->getShared('router')?->notFound([
            'controller' => 'error',
            'action' => 'create',
            'params' => [StatusCode::STATUS_NOT_FOUND],
        ]);

        $container->setShared('dispatcher', function() {
            /** @var Manager $eventsManager */
            /** @var Di $this */

            $eventsManager = $this->getShared('eventsManager');
            $eventsManager->attach('dispatch', new Listeners\HttpMethodListener([
                Http::METHOD_GET,
                Http::METHOD_POST,
                Http::METHOD_PUT,
                Http::METHOD_PATCH
            ]));
            $eventsManager->attach('dispatch', new Listeners\ErrorListener());
            $eventsManager->attach('dispatch', new Listeners\ApiResponseListener());

            /** @var Router $router */
            $router = $this->getShared('router');
            $apiVersion = $router->getParams()['version'];

            $dispatcher = new Dispatcher();
            $defaultNs = __NAMESPACE__ . '\\Controllers';
            $ns = sprintf('%s\\%s', $defaultNs, $apiVersion);
            if (! class_exists(sprintf('%s\\ErrorController', $ns))) {
                $ns = $defaultNs;
            }
            $dispatcher->setDefaultNamespace($ns);
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}
