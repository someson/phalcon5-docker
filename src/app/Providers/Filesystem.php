<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Symfony\Component\Filesystem\Filesystem as Fs;

class Filesystem implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'fs';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            return new Fs();
        });
    }
}
