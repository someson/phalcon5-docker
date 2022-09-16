<?php

namespace App\Providers;

use Library\Db\Dialect\MysqlExtended;
use Phalcon\Config\Config;
use Phalcon\Config\Exception as ConfigException;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Db\Adapter\PdoFactory;

class Database implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'db';

    /**
     * @param DiInterface $di
     * @throws ConfigException
     */
    public function register(DiInterface $di): void
    {
        /** @var Config $config */
        $config = $di->getShared('config');

        /** @var Config $db */
        if (! $db = $config->path('database.web')) {
            throw new ConfigException('Database configuration not specified');
        }

        $di->setShared(self::SERVICE_NAME, function() use ($db) {
            /** @var Config $params */
            $params = $db->merge([
                'options' => [
                    'dialectClass' => MysqlExtended::class,
                    'options' => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . env('MYSQL_CHARSET'),
                        \PDO::ATTR_EMULATE_PREPARES => false,
                        \PDO::ATTR_STRINGIFY_FETCHES => false,
                    ],
                ],
            ]);
            return (new PdoFactory())->load($params);
        });
    }
}
