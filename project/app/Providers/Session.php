<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Manager;
use Phalcon\Session\ManagerInterface;

class Session implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'session';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function() {

            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.sid_length', 32);

            $sessionManager = new Manager();
            $sessionManager->setAdapter(new Stream(['savePath' => '/var/www/html/storage/tmp']));
            if ($sessionManager->status() === ManagerInterface::SESSION_NONE) {
                $sessionManager->setName('SID');
                $sessionManager->start();
            }
            return $sessionManager;
        });
    }
}
