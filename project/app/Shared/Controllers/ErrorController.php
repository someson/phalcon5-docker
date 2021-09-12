<?php

namespace App\Shared\Controllers;

use App\Dispatcher;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Mvc\View;

class ErrorController extends ControllerBase
{
    public function initialize(): void
    {
        $this->view->disableLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setMainView('error');
    }

    protected function prepareErrorTemplate(int $statusCode): void
    {
        $this->tag::setTitle($statusCode);
        $this->response->resetHeaders()->setStatusCode($statusCode);

        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->getDI()->getShared('dispatcher');
        if ($exception = $dispatcher?->getUserOptions()?->has('exceptionData')) {
            $this->view->setVar('exceptionData', (object) $exception);
        }
        $this->view->setVars([
            'errCode' => $statusCode,
            'errMessage' => $exception->message ?? $statusCode,
            'site' => $this->getDI()->getShared('config')->get('app'),
        ]);
    }

    public function badRequestAction(): void
    {
        $this->prepareErrorTemplate(StatusCode::STATUS_BAD_REQUEST);
    }

    public function notFoundAction(): void
    {
        $this->prepareErrorTemplate(StatusCode::STATUS_NOT_FOUND);
    }

    public function internalServerErrorAction(): void
    {
        $this->prepareErrorTemplate(StatusCode::STATUS_INTERNAL_SERVER_ERROR);
    }

    public function unknownErrorAction(): void
    {
        $this->dispatcher->forward([
            'controller' => 'error',
            'action' => 'internalServerError',
        ]);
    }
}
