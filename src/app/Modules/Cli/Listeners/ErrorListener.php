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
            $action = match ($exception->getCode()) {
                BaseException::EXCEPTION_INVALID_HANDLER, BaseException::EXCEPTION_CYCLIC_ROUTING => 'internalServerError',
                BaseException::EXCEPTION_HANDLER_NOT_FOUND, BaseException::EXCEPTION_ACTION_NOT_FOUND => 'notFound',
                BaseException::EXCEPTION_INVALID_PARAMS => 'badRequest',
                default => 'unknownError',
            };
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
