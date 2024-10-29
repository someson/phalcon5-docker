<?php

namespace App\Shared\Listeners;

use App\Env;
use App\Shared\Dispatcher;
use App\Shared\ExceptionDto;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;

class ErrorListener
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Throwable $exception): \Throwable|\Exception|bool
    {
        $e = new \SplObjectStorage();
        $e->attach(new ExceptionDto(\get_class($exception), $exception->getMessage()));
        $dispatcher->getUserOptions()->set('exceptionData', $e);

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
