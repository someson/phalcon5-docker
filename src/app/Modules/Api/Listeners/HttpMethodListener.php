<?php

namespace App\Modules\Api\Listeners;

use App\Shared\Dispatcher;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Http\Message\RequestMethodInterface as Http;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;

class HttpMethodListener extends Injectable
{
    public function __construct(private array $allowedMethods)
    {
        $this->allowedMethods = array_merge([Http::METHOD_OPTIONS], array_unique($this->allowedMethods));
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher): bool
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        if (! in_array($this->request->getMethod(), $this->allowedMethods, true)) {
            $this->response->setStatusCode(StatusCode::STATUS_METHOD_NOT_ALLOWED);
            $this->setDefaultHeaders();
            $event->stop();
        }
        return ! $event->isStopped();
    }

    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher): void
    {
        if ($this->request->getMethod() === Http::METHOD_OPTIONS) {
            $this->response->setStatusCode(StatusCode::STATUS_NO_CONTENT);
            $this->setDefaultHeaders();
        }
    }

    private function setDefaultHeaders(): void
    {
        $this->response->setHeader('Access-Control-Allow-Methods', implode(',', $this->allowedMethods));
        $this->response->setHeader('Access-Control-Allow-Headers',
            'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization'
        );
    }
}
