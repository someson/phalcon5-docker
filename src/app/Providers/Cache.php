<?php

namespace App\Providers;

use App\Env;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Cache\{ AdapterFactory, CacheFactory };
use Phalcon\Storage\SerializerFactory;

class Cache implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $config = $di->getShared('config');
        $cacheServices = $config->cache->toArray();
        foreach ($cacheServices as $serviceName => $config) {
            $di->set($serviceName, function() use ($config) {
                /** @var Di $this */

                $cacheFactory = new CacheFactory(new AdapterFactory(new SerializerFactory()));
                $cache = $cacheFactory->load($config);
                if (isset($options['adapter']) && $options['adapter'] === 'stream') {
                    /** @var \Symfony\Component\Filesystem\Filesystem $fs */
                    $fs = $this->getShared('fs');
                    $fs->mkdir($config['options']['storageDir']);
                }
                if (! Env::isProduction()) {
                    $cache->clear();
                }
                return $cache;
            }, $options['shared'] ?? true);
        }
    }
}
