<?php

namespace Library\Cli\CommandOptions;

use Library\Cli\{ AbstractOption, Output };

final class Verbose extends AbstractOption
{
    public function initialize(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function onStart(): void
    {
    }

    public function onFinish(): void
    {
        $startTime = $_SERVER['REQUEST_TIME'] ?? time();
        $debugData = [
            'task' => $this->_config['runtime']['task'],
            'action' => $this->_config['runtime']['action'],
            'hostname' => php_uname('n'),
            'pid' => getmypid(),
            'start time' => date('d.m.Y H:i:s', $startTime),
            'end time' => date('d.m.Y H:i:s'),
            'total time' => microtime(true) - $startTime,
        ];
        $longest = 0;
        foreach (array_keys($debugData) as $value) {
            $length = strlen($value);
            if ($length > $longest) {
                $longest = $length;
            }
        }
        Output::debug('');
        foreach ($debugData as $key => $value) {
            Output::debug(sprintf('%s : %s', str_pad($key, $longest, ' ', STR_PAD_LEFT), $value));
        }
    }
}
