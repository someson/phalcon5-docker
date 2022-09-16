<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Config\ConfigFactory;

class Config implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'config';

    public function register(DiInterface $di): void
    {
        $config = (new ConfigFactory())->load([
            'filePath' => sprintf('%s/Config/%s', APP_DIR, env('CONFIG_MAIN')),
            'adapter'  => env('CONFIG_ADAPTER'),
        ]);
        $di->setShared(self::SERVICE_NAME, $config);
    }
}
