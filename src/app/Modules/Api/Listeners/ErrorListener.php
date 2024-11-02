<?php

namespace App\Modules\Api\Listeners;

use App\Modules\Api\Exceptions\AcceptableException;
use App\Shared\Dispatcher;
use App\Shared\ExceptionDto;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;
use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;

class ErrorListener
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Throwable $exception): bool
    {
        $e = new \SplObjectStorage();
        $e->attach(new ExceptionDto($exception::class, $exception->getMessage()));
        $dispatcher->getUserOptions()->set('exceptionData', $e);

        if ($exception instanceof DispatchException) {
            $httpStatusCode = match ($exception->getCode()) {
                DispatchException::EXCEPTION_HANDLER_NOT_FOUND,
                DispatchException::EXCEPTION_ACTION_NOT_FOUND => StatusCode::STATUS_NOT_FOUND,
                DispatchException::EXCEPTION_INVALID_PARAMS => StatusCode::STATUS_BAD_REQUEST,
                default => StatusCode::STATUS_INTERNAL_SERVER_ERROR,
            };
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'internal',
                'params' => [$httpStatusCode],
            ]);
            $event->stop();
        }

        if ($exception instanceof AcceptableException && ! $event->isStopped()) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'acceptable',
                'params' => [$exception],
            ]);
            $event->stop();
        }

        if (! $event->isStopped()) {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'unknown',
                'params' => [$exception],
            ]);
            $event->stop();
        }

        return ! $event->isStopped();
    }
}
