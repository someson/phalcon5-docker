<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Filter\FilterFactory;

class Filter implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'filter';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            $factory = new FilterFactory();
            return $factory->newInstance();
        });
    }
}
