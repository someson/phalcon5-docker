<?php

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Config\Exception as ConfigException;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Db\Adapter\PdoFactory;
use Library\Db\Dialect\MysqlExtended;

class DatabaseCli implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'dbCli';

    /**
     * @param DiInterface $di
     * @throws ConfigException
     */
    public function register(DiInterface $di): void
    {
        /** @var Config $config */
        $config = $di->getShared('config');

        /** @var Config $db */
        if (! $db = $config->path('database.cli')) {
            throw new ConfigException('CLI Database configuration not specified');
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
