<?php

namespace App\Modules\Api\Listeners;

use App\Shared\Dispatcher;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;

class ApiResponseListener extends Injectable
{
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher): void
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $content = $dispatcher->getReturnedValue();
        $contentType = $this->response->getHeaders()->get('Content-Type');
        if (str_contains($contentType, 'application/pdf')) {
            return;
        }
        if (is_scalar($content)) {
            $content = (array) trim($content);
        }
        if (! $content) {
            $this->response->setStatusCode(StatusCode::STATUS_NO_CONTENT);
            return;
        }
        $this->response->setJsonContent(
            $content, \JSON_NUMERIC_CHECK|\JSON_UNESCAPED_SLASHES|\JSON_UNESCAPED_UNICODE
        );
    }
}
