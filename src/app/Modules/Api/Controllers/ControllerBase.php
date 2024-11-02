<?php

namespace App\Modules\Api\Controllers;

use App\Modules\Api\Attributes\ApiAuth;
use App\Modules\Api\Components\ApiAuthFactory;
use App\Modules\Api\Components\ApiHandler;
use App\Modules\Api\Exceptions\HttpClientException;
use App\Shared\Dispatcher;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Mvc\Controller;

abstract class ControllerBase extends Controller
{
    protected ApiHandler $api;

    /**
     * @throws HttpClientException
     * @throws \ReflectionException
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher): bool
    {
        $reflectionClass = new \ReflectionClass($dispatcher->getControllerClass());
        $reflectionMethod = $reflectionClass->getMethod($dispatcher->getActiveMethod());

        $tokenHandler = null;
        if (! $this->dispatcher->wasForwarded()) {
            foreach ($reflectionMethod->getAttributes() as $attribute) {
                if ($attribute->getName() === ApiAuth::class) {
                    $attrArguments = $attribute->getArguments();
                    $tokenHandler = ApiAuthFactory::create($attrArguments['type'] ?? null)->from($this->request);
                }
            }
        }

        try {
            $this->api = new ApiHandler($tokenHandler);
        } catch (\Throwable) {
            throw new HttpClientException(StatusCode::STATUS_PRECONDITION_FAILED);
        }
        return true;
    }
}
