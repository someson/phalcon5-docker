<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class Filesystem implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'filesystem';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            return new Filesystem();
        });
    }
}
