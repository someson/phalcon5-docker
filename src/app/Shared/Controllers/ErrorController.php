<?php

namespace App\Shared\Controllers;

use App\Shared\Dispatcher;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Mvc\View;
use Phalcon\Support\Collection;

class ErrorController extends ControllerBase
{
    public function initialize(): void
    {
        $this->view->disableLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setMainView('error');
    }

    protected function prepareErrorTemplate(int $statusCode): void
    {
        \Phalcon\Tag::setTitle($statusCode);
        $this->response->resetHeaders()->setStatusCode($statusCode);

        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->getDI()->getShared('dispatcher');
        if ($dispatcher->getUserOptions()->has('exceptionData')) {
            /** @var Collection $exceptionData */
            $exceptionData = $dispatcher->getUserOptions()->get('exceptionData');
            $this->view->setVar('exceptionData', $exceptionData);
        }
        $this->view->setVars([
            'errCode' => $statusCode,
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
