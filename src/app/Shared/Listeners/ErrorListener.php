<?php

namespace App\Shared\Listeners;

use App\Env;
use App\Shared\Dispatcher;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;
use Phalcon\Support\Collection;

class ErrorListener
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Throwable $exception): \Throwable|\Exception|bool
    {
        $dispatcher->getUserOptions()->set('exceptionData', new Collection([
            'class' => \get_class($exception),
            'message' => $exception->getMessage(),
        ]));

        if ($exception instanceof DispatchException) {
            $action = match ($exception->getCode()) {
                DispatchException::EXCEPTION_INVALID_HANDLER, DispatchException::EXCEPTION_CYCLIC_ROUTING => 'internalServerError',
                DispatchException::EXCEPTION_HANDLER_NOT_FOUND, DispatchException::EXCEPTION_ACTION_NOT_FOUND => 'notFound',
                DispatchException::EXCEPTION_INVALID_PARAMS => 'badRequest',
                default => 'unknownError',
            };
            $dispatcher->forward(['controller' => 'error', 'action' => $action]);
            return false;
        }

        if ($exception instanceof \Exception) {
            if (Env::isProduction()) {
                return $exception;
            }
            $event->stop();
        }

        return $event->isStopped();
    }
}
