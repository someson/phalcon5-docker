<?php

namespace App\Providers;

use App\Env;
use App\Shared\VoltFunctions;
use App\View as ExtendedView;
use Phalcon\Di\Di;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\View\Engine\Volt;

class View implements ServiceProviderInterface
{
    public const SERVICE_NAME = 'view';

    public function register(DiInterface $di) : void
    {
        $this->registerEngine($di);
        $di->setShared(self::SERVICE_NAME, function() {
            $view = new ExtendedView();
            $view->registerEngines(['.volt' => 'volt']);
            return $view;
        });
    }

    public function registerEngine(DiInterface $di) : void
    {
        $di->setShared('volt', function($view) {
            $compiledPath = static function($templatePath) {
                [, $path] = explode(BASE_DIR, $templatePath);
                $cacheDir = CACHE_DIR . DS . 'volt' . \dirname($path);
                if (! is_writable($cacheDir) && ! mkdir($cacheDir, 0777, true) && ! is_dir($cacheDir)) {
                    throw new \RuntimeException(sprintf('Directory [%s] was not created', $cacheDir));
                }
                $fileName = basename($path, '.volt') . '.php';
                return rtrim($cacheDir, '\\/') . DS . $fileName;
            };

            /** @var Di $this */
            $volt = new Volt($view, $this);
            $volt->setOptions([
                'path' => $compiledPath,
                'always' => ! Env::isProduction(),
            ]);
            $compiler = $volt->getCompiler();
            $compiler->addExtension(new VoltFunctions());
            return $volt;
        });
    }
}
