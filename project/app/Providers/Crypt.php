<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class Crypt implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'crypt';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            /** @var \Phalcon\Config $config */
            /** @var \Phalcon\Di $this */
            $config = $this->getShared('config');

            $obj = new \Phalcon\Crypt();
            $obj->setKey($config->path('crypt.key'));
            return $obj;
        });
    }
}
