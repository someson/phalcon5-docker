<?php

namespace App;

use DomainException;
use Phalcon\Application\AbstractApplication;
use Phalcon\Di\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Support\Registry;

class Bootstrap
{
    /** @var CliApplication|WebApplication */
    private AbstractApplication $_app;

    public function __construct(bool $autoDetect = true)
    {
        $this->defineConstants($this->identify());
        $isCli = $autoDetect && $this->isCli();
        $container = $isCli ? new FactoryDefault\Cli : new FactoryDefault;

        Di::setDefault($container);

        $this->_app = $isCli ? new CliApplication($container) : new WebApplication($container);
        $this->_app->registerServices($container);

        $registry = new Registry();
        $registry->set('modules', $this->_app->getModules());
        $container->set('registry', $registry);
    }

    /**
     * @return CliApplication|WebApplication
     */
    public function getApplication()
    {
        return $this->_app;
    }

    public function isCli(): bool
    {
        return !isset($_SERVER['SERVER_SOFTWARE'])
            && (\PHP_SAPI === 'cli' || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0));
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
