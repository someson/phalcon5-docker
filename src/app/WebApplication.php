<?php

namespace App;

use App\Shared\Debug;
use App\Shared\Dispatcher;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Application;
use Phalcon\Support\Collection;

class WebApplication extends Application
{
    public function __construct(DiInterface $di)
    {
        parent::__construct($di);
        $this->registerModules([
            'frontend' => [
                'className' => Modules\Frontend\Module::class,
                'routes' => Modules\Frontend\Routes::class,
            ],
        ]);
    }

    public function getProviders(): array
    {
        return [
            Providers\Config::class,
            Providers\Cookies::class,
            Providers\Crypt::class,
            Providers\Database::class,
            Providers\Filter::class,
            Providers\Filesystem::class,
            Providers\Flash::class,
            Providers\Logger::class,
            Providers\Router::class,
            Providers\Security::class,
            Providers\Session::class,
            Providers\View::class,
            Providers\Url::class,
        ];
    }

    public function registerServices(Di $di): void
    {
        foreach ($this->getProviders() as $provider) {
            $di->register(new $provider());
        }
    }

    public function handle(string $uri)
    {
        if ($response = parent::handle($uri)) {
            echo $response->getContent(); // $response->send();
        }
    }

    public function handleException(\Throwable $e)
    {
        if (ob_get_level()) {
            ob_end_clean();
        }
        if (Env::isDevelopment()) {
            /** @var Dispatcher $dispatcher */
            $dispatcher = $this->getDI()->getShared('dispatcher');
            $dispatcher->getUserOptions()->set('exceptionData', new Collection([
                'class' => $e::class,
                'message' => $e->getMessage(),
            ]));
            return (new Debug())->listen($exceptions = true, $errors = true)->onUncaughtException($e);
        }
        require_once 'Micro.php';
    }
}
