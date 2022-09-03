<?php

namespace App\Providers;

use Library\Traits\TraitFilesystem;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Logger\Logger as LogHandler;
use Phalcon\Logger\Adapter\Stream as File;
use Phalcon\Logger\Formatter\Line;

class LoggerCli implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'logger';

    public function register(DiInterface $di): void
    {
        $di->setShared(self::SERVICE_NAME, function(?string $fileName = null) {
            $formatter = new Line('[%type%] %date% : %message%');
            $formatter->setDateFormat('H:i:s');
            $logDir = sprintf('%s/logs/', TMP_DIR);

            if (! TraitFilesystem::checkOrCreate($logDir)) {
                throw new \RuntimeException('Log directory could not be created');
            }
            $fileName = trim($fileName) ?: sprintf('%s_cli.log', date('Y-m-d'));
            $adapter = new File(sprintf('%s%s', $logDir, $fileName), ['mode' => 'a+']);
            $adapter->setFormatter($formatter);

            return new LogHandler('messages', ['local' => $adapter]);
        });
    }
}
