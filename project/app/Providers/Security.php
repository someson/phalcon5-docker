<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Security as PhalconSecurity;

class Security implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'security';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            $obj = new PhalconSecurity();
            $obj->setDefaultHash(PhalconSecurity::CRYPT_BLOWFISH_Y);
            $obj->setWorkFactor(12);
            return $obj;
        });
    }
}
