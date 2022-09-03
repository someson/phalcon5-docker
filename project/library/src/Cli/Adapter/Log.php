<?php

namespace Library\Cli\Adapter;

use Library\Cli\AbstractAdapter;
use Phalcon\Di\Di;
use Phalcon\Logger\LoggerInterface;

class Log extends AbstractAdapter
{
    private LoggerInterface $_provider;

    public function __construct(?string $serviceName = null)
    {
        $container = Di::getDefault();
        $serviceName = $serviceName ?? 'logger';
        if (! $container || ! $container->has($serviceName)) {
            throw new \RuntimeException('Logger service provider not found');
        }
        $this->_provider = $container->getShared($serviceName);
    }

    /**
     * {@inheritDoc}
     */
    public function report(array $attr)
    {
        if (! method_exists($this->_provider, 'error')) {
            throw new \RuntimeException(sprintf('Logger adapter [%s] does not contain expected method [error]', __CLASS__));
        }
        $this->_provider->info(print_r($attr, true));
        return true;
    }
}
