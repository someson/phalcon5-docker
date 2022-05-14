<?php

try {
    $di = new \Phalcon\Di\FactoryDefault();
    $app = new \Phalcon\Mvc\Micro($di);

    defined('CURRENT_APP') || define('CURRENT_APP', $_SERVER['SERVER_NAME'] ?? env('APP_DOMAIN'));

    $file = sprintf('%s/Config/Main.php', APP_DIR);
    if (! file_exists($file)) {
        throw new \Phalcon\Config\Exception('Configuration not defined');
    }

    $defaultConfig = new \Phalcon\Config\Adapter\Php($file);
    $site = $defaultConfig->get('app', []);
    $config = new \Phalcon\Config\Config([
        'debug' => $defaultConfig->get('debug', true),
        'viewsDir' => SHARED_DIR . '/Views/',
        'cacheDir' => CACHE_DIR . '/volt/',
    ]);

    $di->setShared('view', function() use ($config, $site) {
        $view = new Phalcon\Mvc\View\Simple();
        $view->setViewsDir($config->get('viewsDir'));
        $view->registerEngines([
            '.volt' => function($view) use ($config) {
                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $this);
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

    $output = static function(\Phalcon\Mvc\Micro $app, $e) {
        $code = \Phalcon\Http\Message\ResponseStatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
        if ($e instanceof \DomainException) {
            $code = \Phalcon\Http\Message\ResponseStatusCodeInterface::STATUS_FORBIDDEN;
        }
        $message = $app->response->getReasonPhrase();
        $app->response->setStatusCode($code, $message)->sendHeaders();

        /** @var \Phalcon\Mvc\View\Simple $view */
        $view = $app->view;
        return $view->render('error', [
            'errCode' => $code,
            'errMessage' => $message,
            'exceptionData' => (object) [
                'class' => get_class($e),
                'message' => $e->getMessage(),
            ],
        ]);
    };

    $app->error(function() use ($output, $app, $e) { echo $output($app, $e); });
    $app->notFound(function() use ($output, $app, $e) { echo $output($app, $e); });
    $app->handle($_SERVER['REQUEST_URI']);

} catch (\Throwable $e) {
    echo $e->getMessage(), PHP_EOL;
    if (! \App\Env::isProduction()) {
        echo $e->getTraceAsString();
    }
}
