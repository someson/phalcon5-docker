<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class Cookies implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'cookies';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            $cookies = new \Phalcon\Http\Response\Cookies();
            $cookies->useEncryption(true);
            return $cookies;
        });
    }
}
