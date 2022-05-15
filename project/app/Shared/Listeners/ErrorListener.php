<?php

namespace App\Shared\Listeners;

use App\Shared\Dispatcher;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;

class ErrorListener
{
    private static int $_counter = 0;

    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception): bool
    {
        if (++self::$_counter > 1) {
            throw $exception;
        }

        $dispatcher->getUserOptions()->set('exceptionData', [
            'class' => $exception::class,
            'message' => $exception->getMessage(),
        ]);

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

        return $event->isStopped();
    }
}
