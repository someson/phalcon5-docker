<?php

namespace App\Providers;

use Phalcon\Flash\Direct;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class Flash implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'flash';

    public function register(DiInterface $di): void
    {
        $options = [
            'warning' => 'alert alert-warning',
            'notice'  => 'alert alert-info',
            'success' => 'alert alert-success',
            'error'   => 'alert alert-danger',
        ];

        $di->setShared(self::SERVICE_NAME, function() use ($options) {
            $flash = new Direct();
            $flash->setCssClasses($options);
            $flash->setAutoescape(false);
            $flash->setCustomTemplate('<div class="%cssClass%" role="alert">%message%</div>');
            return $flash;
        });

        $di->setShared(self::SERVICE_NAME . 'Session', function() use ($options) {
            $flash = new FlashSession();
            $flash->setCssClasses($options);
            $flash->setAutoescape(false);
            $flash->setCustomTemplate('<div class="%cssClass%" role="alert">%message%</div>');
            return $flash;
        });
    }
}
