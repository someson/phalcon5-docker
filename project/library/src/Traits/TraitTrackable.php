<?php

namespace Library\Traits;

use Phalcon\Filter\Filter;
use Phalcon\Http\Request;
use Phalcon\Http\Response\Cookies;

trait TraitTrackable
{
    /** @var bool */
    protected bool $_isTracked = true;

    /** @var string */
    protected string $_trackName;

    /**
     * @param string $queryName
     * @param array $excludeIPs
     */
    public function makeTrackable(string $queryName, array $excludeIPs = []): void
    {
        $this->_trackName = $queryName;

        /** @var Cookies $cookieService */
        $cookieService = $this->getDI()->getShared('cookies');
        $this->view->setVar('_track', $this->checkTrackable($this->request, $cookieService, $excludeIPs));
    }

    /**
     * @param Request $request
     * @param Cookies $cookieService
     * @param array $excludeIPs
     * @return bool
     */
    public function checkTrackable(Request $request, Cookies $cookieService, array $excludeIPs = []): bool
    {
        if ($request->hasQuery($this->_trackName)) {
            $this->_isTracked = ! (bool) $request->getQuery($this->_trackName, Filter::FILTER_INT, 0);
            $cookieService->set($this->_trackName, ! $this->_isTracked, time() + 2592000 /* 30 days */);
        } else if ($cookieService->has($this->_trackName)) {
            $this->_isTracked = ! (bool) $cookieService->get($this->_trackName)->getValue('int!', 0);
        }
        if ($this->_isTracked && $excludeIPs) {
            $this->_isTracked = ! \in_array($request->getClientAddress(), $excludeIPs, true);
        }
        return $this->_isTracked;
    }

    public function isTracked(): bool
    {
        return $this->_isTracked;
    }
}
