<?php

set_time_limit(0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

require_once dirname(__DIR__) . '/app/Constants.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

if (! function_exists('env')) {
    function env($name) {
        return \App\Env::get($name);
    }
}

try {
    try {
        (new \App\Env(dirname(__DIR__)))->load();
    } catch (\RuntimeException $e) {
        throw new \Phalcon\Cli\Console\Exception($e->getMessage(), 1);
    }
    array_shift($argv); // no need the script name in array
} catch (Exception $e) {
    \Library\Cli\Output::error($e->getMessage());
    exit(255);
}

$errorHandler = new \Library\Cli\PhpError(
    new \Library\Cli\Reporter(function() {
        return [
            new \Library\Cli\Adapter\Log('logger'),
            new \Library\Cli\Adapter\Mysql(\Library\Cli\Models\TaskRuntime::class),
        ];
    })
);
register_shutdown_function([$errorHandler, 'runtimeShutdown']);
set_error_handler([$errorHandler, 'errorHandler']);

try {
    $bootstrap = new \App\Bootstrap();
    $console = $bootstrap->getApplication();
    $console->setArgument($argv, $asString = false, $shift = false)->handle();

    /**
     * $ docker-compose exec app-service php ./scripts/cli.php main main -s -v -r
     * $console->getArguments():
     * Array
     * (
     *    [task] => main
     *    [action] => main
     * )
     *
     * $console->getOptions():
     * Array
     * (
     *    [s] => 1
     *    [v] => 1
     *    [r] => 1
     * )
     */

} catch (\Throwable $e) {
    if (isset($console)) {
        $console->handleException($e);
        /** @var \SplPriorityQueue $queue */
        $queue = $console->getEventsManager()->getListeners('console');
        foreach ($queue as $listener) {
            if ($listener instanceof \Library\Cli\Listeners\TaskListener) {
                $handler = $listener->getHandler();
                $handler->finishTask();
            }
        }
    } else {
        \Library\Cli\Output::error($e->getMessage());
    }
    exit(255);
}
