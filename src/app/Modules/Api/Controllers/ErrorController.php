<?php

namespace App\Modules\Api\Controllers;

use App\Env;
use App\Modules\Api\Components\ErrorContent;
use App\Modules\Api\Exceptions\CollectableInterface;
use App\Shared\Dispatcher;
use App\Shared\Notification;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{
    public function internalAction(int $statusCode): array
    {
        $this->response->resetHeaders()->setStatusCode($statusCode);
        $content = new ErrorContent($statusCode);
        if (! Env::isProduction()) {
            /** @var Dispatcher $dispatcher */
            $dispatcher = $this->getDI()->getShared('dispatcher');
            if ($dispatcher->getUserOptions()->has('exceptionData')) {
                /** @var \SplObjectStorage $exceptionData */
                $exceptionData = $dispatcher->getUserOptions()->get('exceptionData');
                $content->setDebugMessage($exceptionData->current()->message);
            }
        }
        return $content->get();
    }

    /**
     * @throws \Exception
     */
    public function unknownAction(\Throwable $e): array
    {
        $statusCode = $e->getCode() > StatusCode::STATUS_BAD_REQUEST ?
            $e->getCode() : StatusCode::STATUS_INTERNAL_SERVER_ERROR;

        $this->response->resetHeaders()->setStatusCode($statusCode);
        $content = new ErrorContent($statusCode);

        if ($e instanceof CollectableInterface) {
            $content->setParam('errors', $e->getMessages());
        }
        if (! Env::isProduction()) {
            $content->setDebugMessage($e->getMessage());
        }
        return $content->get();
    }

    public function acceptableAction(\Throwable $e): Notification
    {
        return Notification::success($e->getMessage())->with(['data' => []]);
    }
}
