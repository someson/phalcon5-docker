<?php

namespace App\Providers;

use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class Url implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'url';

    public function register(DiInterface $di) : void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            /** @var \Phalcon\Config\Config $config */
            /** @var Di $this */
            $config = $this->getShared('config');
            $tldConfig = $config->get('app');

            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri($tldConfig->baseUri ?? '/');
            $url->setStaticBaseUri($tldConfig->staticUri ?? '/');
            return $url;
        });
    }
}
