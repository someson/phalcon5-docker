<?php

namespace App;

use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Application;

class WebApplication extends Application
{
    public function __construct(DiInterface $di)
    {
        $this->registerModules([
            'frontend' => [
                'className' => Modules\Frontend\Module::class,
                'routes' => Modules\Frontend\Routes::class,
            ],
        ]);
        parent::__construct($di);
    }

    public function getProviders(): array
    {
        return [
            Providers\Config::class,
            Providers\Cookies::class,
            Providers\Crypt::class,
            Providers\Filter::class,
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
        if ($api = (stripos(trim($uri, '/ '), 'api/') === 0)) {
            $this->useImplicitView(false);
        }
        /** @var ResponseInterface|false $response */
        $response = parent::handle($uri);
        if (! $api && $response instanceof ResponseInterface) {
            echo $response->getContent();
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
            $dispatcher->getUserOptions()->set('exceptionData', [
                'class' => \get_class($e),
                'message' => $e->getMessage(),
            ]);
            return (new Debug())->listen($exceptions = true, $errors = true)->onUncaughtException($e);
        }
        require_once 'Micro.php';
    }
}
