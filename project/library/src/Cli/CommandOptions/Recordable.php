<?php

namespace Library\Cli\CommandOptions;

use Library\Cli\Adapter\Mysql;
use Library\Cli\{ AbstractOption, Output, Reporter };

final class Recordable extends AbstractOption
{
    private Reporter $_reporter;

    /**
     * 'cli' => [
     *    'reports' => [
     *        [
     *            'className' => \Library\Cli\Adapter\Mysql::class,
     *            'args' => [\Library\Cli\Models\Task::class]
     *        ],[
     *            'className' => \Library\Cli\Adapter\Log::class,
     *            'args' => ['logger'],
     *        ],
     *    ],
     * ],
     * @return void
     */
    public function initialize(): void
    {
        $this->_reporter = new Reporter(function() {
            return array_map(static function($item) {
                return new $item['className'](...$item['args']);
            }, $this->_config['reports']);
        });
    }

    public function getReporter(): Reporter
    {
        return $this->_reporter;
    }

    public function onStart(): void
    {
        if ($this->_reporter->has(Mysql::class)) {
            /** @var Mysql $adapter */
            $adapter = $this->_reporter->getAdapter(Mysql::class);
            $this->_reporter->reportBy($adapter, [
                'script_name' => $_SERVER['PHP_SELF'],
                'task_name' => $this->_config['runtime']['task'],
                'action_name' => $this->_config['runtime']['action'],
            ]);
            $results = $this->_reporter->getResultsBy(Mysql::class);
            if ($results instanceof \Exception) {
                Output::error($results->getMessage());
            } elseif (is_numeric($results)) {
                $adapter->addOption('lastId', $results);
            }
        }
    }

    public function onFinish(): void
    {
        $errors = Output::getError();
        $reporter = $this->getReporter();
        $reporter->report([
            'exit_status' => 0,
            'state' => $errors ? 'FAIL' : 'SUCCESS',
            'stdout' => Output::getText(),
            'stderr' => $errors,
            'script_name' => $_SERVER['PHP_SELF'],
            'task_name' => $this->_config['runtime']['task'],
            'action_name' => $this->_config['runtime']['action'],
        ]);
        if ($reporter->has(Mysql::class)) {
            $results = $reporter->getResultsBy(Mysql::class);
            if ($results instanceof \Exception) {
                Output::error($results->getMessage());
            }
        }
    }
}
