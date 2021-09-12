<?php

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Logger as LogHandler;
use Phalcon\Logger\Adapter\Stream as File;
use Phalcon\Logger\Formatter\Line;

class Logger implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'logger';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function(string $fileName = null) {
            $formatter = new Line('[%type%] %date% : %message%');
            $formatter->setDateFormat('H:i:s');

            $fileName = trim($fileName) ?: date('Y-m-d') . '.log';
            $logDir = sprintf('%s/storage/logs/', BASE_DIR);
            if (! @mkdir($logDir, 0777, true) && ! is_dir($logDir)) {
                throw new \RuntimeException('Log directory could not be created');
            }
            $adapter = new File(sprintf('%s%s', $logDir, $fileName), ['mode' => 'a+']);
            $adapter->setFormatter($formatter);

            return new LogHandler('messages', ['local' => $adapter]);
        });
    }
}
