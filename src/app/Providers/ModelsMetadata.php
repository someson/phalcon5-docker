<?php

namespace App\Providers;

use App\Env;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Model\MetaData\{ Memory, Stream };
use Phalcon\Storage\SerializerFactory;

class ModelsMetadata implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'modelsMetadata';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            /** @var Di $this */
            $config = $this->getShared('config');
            if ($metaDataConfig = $config->path('database.metaDataCache')) {
                $serviceConfig = isset($metaDataConfig['adapter']) ? $metaDataConfig->toArray() : [
                    'adapter' => 'stream',
                    'options' => [
                        'prefix' => CURRENT_APP . '-',
                        'metaDataDir' => CACHE_DIR . DS . 'meta' . DS,
                    ],
                ];
                if (Env::isProduction()) {
                    $options = $serviceConfig['options'] ?? [];
                    if (isset($serviceConfig['adapter']) && $serviceConfig['adapter'] === 'stream') {
                        if ($dir = $serviceConfig['options']['metaDataDir'] ?? null)  {
                            /** @var \Symfony\Component\Filesystem\Filesystem $fs */
                            $fs = $this->getShared('fs');
                            $fs->mkdir($dir);
                        }
                        return new Stream($options);
                    }
                    $adapter = '\\Phalcon\\Mvc\\Model\\MetaData\\' . ucfirst($serviceConfig['adapter']);
                    return new $adapter(new AdapterFactory(new SerializerFactory()), $options);
                }
            }
            $metaData = new Memory(['lifetime' => 0]);
            $metaData->reset();

            return $metaData;
        });
    }
}
