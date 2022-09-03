<?php

namespace App\Shared\Listeners;

use App\Env;
use App\Shared\Dispatcher;
use Phalcon\Dispatcher\Exception as DispatchException;
use Phalcon\Events\Event;
use Phalcon\Support\Collection;

class ErrorListener
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Throwable $exception)
    {
        $dispatcher->getUserOptions()->set('exceptionData', new Collection([
            'class' => \get_class($exception),
            'message' => $exception->getMessage(),
        ]));

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

        if ($exception instanceof \Exception) {
            if (Env::isProduction()) {
                return $exception;
            }
            $event->stop();
        }

        return $event->isStopped();
    }
}
