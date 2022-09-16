<?php

namespace Library\Cli;

use Exception, UnexpectedValueException;

class Reporter
{
    /** @var array<int, Interfaces\Reportable> */
    protected $_adapters;
    protected array $_results;

    public function __construct(callable $adapters)
    {
        $this->_adapters = $adapters;
    }

    public function has(string $adapterClass): bool
    {
        $adapters = $this->getAdapters();
        foreach ($adapters as $adapter) {
            if ($adapter instanceof $adapterClass) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<string, mixed> $attr
     */
    public function report(array $attr): void
    {
        $adapters = $this->getAdapters();
        foreach ($adapters as $adapter) {
            $this->reportBy($adapter, $attr);
        }
    }

    /**
     * @param Interfaces\Reportable $adapter
     * @param array<string, mixed> $attr
     */
    public function reportBy(Interfaces\Reportable $adapter, array $attr): void
    {
        $id = $adapter->getName();
        try {
            $this->_results[$id] = $adapter->report($attr);
        } catch (Exception $e) {
            $this->_results[$id] = $e;
        }
    }

    public function getAdapter(string $adapterClass): Interfaces\Reportable
    {
        $adapters = $this->getAdapters();
        foreach ($adapters as $adapter) {
            if ($adapterClass === $adapter->getName()) {
                return $adapter;
            }
        }
        throw new UnexpectedValueException(sprintf('Adapter [%s] not found', $adapterClass));
    }

    protected function getAdapters(): array
    {
        if (is_callable($this->_adapters)) {
            $adapters = (array) call_user_func($this->_adapters);
            $this->_adapters = array_filter($adapters, static function ($adapter) {
                return $adapter instanceof Interfaces\Reportable;
            });
        }
        return $this->_adapters;
    }

    public function getResults(): array
    {
        return $this->_results;
    }

    public function getResultsBy(string $adapterClass)
    {
        return $this->getResults()[$adapterClass] ?? [];
    }
}
