<?php

namespace App;

use DomainException;
use Phalcon\{ Di, Registry };
use Phalcon\Di\FactoryDefault;

class Bootstrap
{
    private WebApplication $_app;

    public function __construct()
    {
        $this->defineConstants($this->identify());

        $di = new FactoryDefault;
        Di::setDefault($di);

        $this->_app = new WebApplication($di);
        $this->_app->registerServices($di);

        $registry = new Registry();
        $registry->set('modules', $this->_app->getModules());
        $di->set('registry', $registry);
    }

    public function getApplication(): WebApplication
    {
        return $this->_app;
    }

    public function identify(): string
    {
        $domain = $_SERVER['SERVER_NAME'] ?? env('DEFAULT_DOMAIN');
        $parts = explode('.', $domain);
        $tld = strtolower(end($parts));

        // if an IP requested
        if (is_numeric($tld)) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                throw new DomainException('Request a domain name instead of IP');
            }
            return env('DEFAULT_DOMAIN');
        }
        return $domain;
    }

    public function defineConstants(string $domain): void
    {
        $parts = explode('.', $domain);

        \defined('APP_ENV')     || \define('APP_ENV', env('APP_ENV'));
        \defined('CURRENT_APP') || \define('CURRENT_APP', $domain);
        \defined('CURRENT_TLD') || \define('CURRENT_TLD', strtolower(end($parts)));
    }
}
