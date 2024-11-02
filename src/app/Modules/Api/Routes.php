<?php

namespace App\Modules\Api;

use Phalcon\Http\Request;
use Phalcon\Mvc\Router\Group as RouterGroup;
use Phalcon\Support\Helper\Str\Camelize;

class Routes extends RouterGroup
{
    public function initialize(): void
    {
        $method = strtolower((new Request)->getMethod());
        $this->setPrefix('/api/{version:(v\d)}');
        $camelize = new Camelize;

        $this->setPaths([
            'module' => 'api',
            'controller' => 'index',
            'action' => $method,
        ]);

        $this->add('/:controller/:int', [
            'controller' => 2,
            'action' => $method,
            'id' => 3,
        ])->convert('controller', function($controller) use ($camelize) {
            return lcfirst($camelize($controller));
        });

        $this->add('/:controller[/]?', [
            'controller' => 2,
            'action' => $method,
        ])->convert('controller', function($controller) use ($camelize) {
            return lcfirst($camelize($controller));
        });

        $this->add('[/]?', [])->setName('apiBase');
    }
}
