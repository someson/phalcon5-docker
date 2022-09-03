<?php

namespace App\Providers;

use Library\Session\Adapter\Mysql;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Manager;
use Phalcon\Session\ManagerInterface;

class Session implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'session';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {
            /** @var Di $this */

            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.sid_length', 32);

            //$adapter = new \Phalcon\Session\Adapter\Stream(['savePath' => '/var/www/storage/tmp']);
            $adapter = new Mysql([
                'connection' => $this->getShared('db'),
                'logger' => $this->getShared('logger'),
                'lifetime' => 86400,
                'ignoring_delay' => 900,
            ]);

            $sessionManager = new Manager();
            $sessionManager->setAdapter($adapter);
            if ($sessionManager->status() === ManagerInterface::SESSION_NONE) {
                $sessionManager->setName('SID');
                $sessionManager->start();
            }
            return $sessionManager;
        });
    }
}
