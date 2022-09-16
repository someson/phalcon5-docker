<?php

namespace App\Providers;

use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Encryption\Crypt as Encryption;

class Crypt implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'crypt';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            /** @var \Phalcon\Config\Config $config */
            /** @var Di $this */
            $config = $this->getShared('config');

            $obj = new Encryption();
            $obj->setKey($config->path('crypt.key'));
            return $obj;
        });
    }
}
