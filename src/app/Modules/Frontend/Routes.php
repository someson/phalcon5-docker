<?php

namespace App\Modules\Frontend;

use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    public function initialize(): void
    {
        $this->setPaths([
            'module' => 'frontend',
            'controller' => 'index',
            'action' => 'index',
        ]);

        $this->add('/:controller/:action/:params', [
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ]);

        $this->add('/:controller/:action', [
            'controller' => 1,
            'action'     => 2,
        ]);

        $this->add('/:controller', ['controller' => 1]);

        $this->add('/', [])->setName('frontendBase');
    }
}
