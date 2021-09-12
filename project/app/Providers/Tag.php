<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class Tag implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'tag';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            return new \App\Tag();
        });
    }
}
