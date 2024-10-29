<?php

use App\Shared\ExceptionDto;
use App\Shared\Micro;
use App\Shared\Simple;
use App\Shared\Volt;
use Phalcon\Config\Adapter\Php;
use Phalcon\Config\Config;
use Phalcon\Config\Exception as ConfigException;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Message\ResponseStatusCodeInterface;

try {
    $di = new FactoryDefault();
    $app = new Micro($di);

    defined('CURRENT_APP') || define('CURRENT_APP', $_SERVER['SERVER_NAME'] ?? env('APP_DOMAIN'));

    $file = sprintf('%s/Config/Main.php', APP_DIR);
    if (! file_exists($file)) {
        throw new ConfigException('Configuration not defined');
    }
    $defaultConfig = new Php($file);
    $site = $defaultConfig->get('app', []);
    $config = new Config([
        'debug' => $defaultConfig->get('debug', true),
        'viewsDir' => SHARED_DIR . '/Views/',
        'cacheDir' => CACHE_DIR . '/volt/',
    ]);

    $voltCacheDir = $config->get('cacheDir');
    if (! is_writable($voltCacheDir) && ! mkdir($voltCacheDir, 0777, true) && ! is_dir($voltCacheDir)) {
        throw new \RuntimeException(sprintf('Directory [%s] was not created', $voltCacheDir));
    }

    $di->setShared('view', function() use ($config, $site) {
        $view = new Simple();
        $view->setViewsDir($config->get('viewsDir'));
        $view->registerEngines([
            '.volt' => function($view) use ($config) {
                $volt = new Volt($view, $this);
                $volt->setOptions([
                    'path' => $config->get('cacheDir'),
                    'separator' => '_',
                ]);
                return $volt;
            }
        ]);
        $view->setVar('config', $config);
        $view->setVar('site', $site);
        return $view;
    });

    /**
     * @param Micro $app
     * @param array<int, \Throwable> $exceptions
     * @return string
     */
    $output = static function(Micro $app, array $exceptions) {
        $code = ResponseStatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
        if ($exceptions[0] instanceof \DomainException) {
            $code = ResponseStatusCodeInterface::STATUS_FORBIDDEN;
        }
        $message = $app->response->getReasonPhrase();
        $app->response->setStatusCode($code, $message)->sendHeaders();

        $storage = new \SplObjectStorage();
        foreach ($exceptions as $exception) {
            $storage->attach(new ExceptionDto($exception::class, $exception->getMessage()));
        }
        /** @var Simple $view */
        $view = $app->view;
        return $view->render('error', [
            'errCode' => $code,
            'exceptionData' => $storage,
        ]);
    };

    /** @var \Throwable $e from the catch block where this file is included */
    $app->error(function(\Throwable $appException) use ($output, $app, $e) {
        $exceptions = $appException->getMessage() === $e->getMessage() ? [$e] : [$appException, $e];
        echo $output($app, $exceptions);
    });
    $app->notFound(function() use ($output, $app, $e) { echo $output($app, [$e]); });

    try {
        $app->handle($_SERVER['REQUEST_URI']);
    } catch(\Throwable) {
        // nothing to do
    }

} catch (\Throwable $e) {
    echo $e->getMessage(), PHP_EOL;
    if (! \App\Env::isProduction()) {
        echo $e->getTraceAsString();
    }
}
