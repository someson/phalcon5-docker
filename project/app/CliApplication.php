<?php

namespace App;

use App\Modules\Cli\Module as CliModule;
use Library\Cli\Listeners\TaskListener;
use Library\Cli\{ Output, Application };
use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;

class CliApplication extends Application
{
    public function __construct(DiInterface $di)
    {
        $this->registerModules([
            'cli' => [
                'className' => CliModule::class,
            ],
        ]);
        parent::__construct($di);
    }

    public function registerServices(Di $di): void
    {
        foreach ($this->getProviders() as $provider) {
            $di->register(new $provider());
        }
    }

    public function getProviders(): array
    {
        return [
            Providers\Config::class,
            Providers\Crypt::class,
            Providers\DatabaseCli::class,
            Providers\LoggerCli::class,
            Providers\ModelsMetadata::class,
            Providers\RouterCli::class,
        ];
    }

    /**
     * @param array|null $arguments
     * @return void
     */
    public function handle(?array $arguments = null)
    {
        /** @var Manager $eventaManager */
        $eventaManager = $this->getDI()->getShared('eventsManager');

        /** @var Config $config */
        $config = $this->getDI()->getShared('config');

        $eventaManager->attach('console', new TaskListener($config->path('cli')));
        $this->setEventsManager($eventaManager);

        parent::handle($arguments);
    }

    public function handleException(\Throwable $e): void
    {
        $sub = '%s[ERROR]%s %s. [File %s, Line %u]';
        $msg = sprintf($sub, Output::COLOR_RED, Output::COLOR_NONE, $e->getMessage(), $e->getFile(), $e->getLine());
        Output::error($msg);
    }
}
