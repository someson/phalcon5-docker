<?php

namespace App\Shared\Listeners;

use App\Shared\Dispatcher;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;

class ErrorListener
{
    private static int $_counter = 0;

    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        if (++self::$_counter > 1) {
            throw $exception;
        }

        $dispatcher->getUserOptions()->set('exceptionData', [
            'class' => \get_class($exception),
            'message' => $exception->getMessage(),
        ]);

        if ($exception instanceof DispatchException) {
            switch ($exception->getCode()) {
                case DispatchException::EXCEPTION_INVALID_HANDLER:
                case DispatchException::EXCEPTION_CYCLIC_ROUTING:
                    $action = 'internalServerError';
                    break;
                case DispatchException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatchException::EXCEPTION_ACTION_NOT_FOUND:
                    $action = 'notFound';
                    break;
                case DispatchException::EXCEPTION_INVALID_PARAMS:
                    $action = 'badRequest';
                    break;
                default:
                    $action = 'unknownError';
            }
            $dispatcher->forward(['controller' => 'error', 'action' => $action]);
            return false;
        }

        return $event->isStopped();
    }
}
