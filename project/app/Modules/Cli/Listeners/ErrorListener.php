<?php

namespace App\Modules\Cli\Listeners;

use Library\Cli\Dispatcher;
use Phalcon\Cli\Task;
use Phalcon\Cli\Dispatcher\Exception as DispatchException;
use Phalcon\Dispatcher\Exception as BaseException;
use Phalcon\Events\Event;
use Phalcon\Support\Collection;

class ErrorListener
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Throwable $exception): bool
    {
        $dispatcher->getUserOptions()->set('exceptionData', new Collection([
            'class' => \get_class($exception),
            'message' => $exception->getMessage(),
        ]));

        if ($exception instanceof DispatchException) {
            switch ($exception->getCode()) {
                case BaseException::EXCEPTION_INVALID_HANDLER:
                case BaseException::EXCEPTION_CYCLIC_ROUTING:
                    $action = 'internalServerError';
                    break;
                case BaseException::EXCEPTION_HANDLER_NOT_FOUND:
                case BaseException::EXCEPTION_ACTION_NOT_FOUND:
                    $action = 'notFound';
                    break;
                case BaseException::EXCEPTION_INVALID_PARAMS:
                    $action = 'badRequest';
                    break;
                default:
                    $action = 'unknownError';
            }
            $dispatcher->forward(['task' => 'error', 'action' => $action]);
            return true;
        }

        $dispatcher->forward(['task' => 'error', 'action' => 'unknownError']);
        if (! $dispatcher->getLastTask() instanceof Task) {
            $event->stop();
        }

        return $event->isStopped();
    }
}
