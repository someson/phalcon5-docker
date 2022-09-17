<?php

namespace App\Modules\Cli\Tasks;

use Library\Cli\{ Dispatcher, Output };
use Phalcon\Cli\Task;
use Phalcon\Support\Collection;

class ErrorTask extends Task
{
    private function _getExceptionMessage(): string
    {
        $unknown = 'Unknown Error';
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->getDI()->getShared('dispatcher');
        if ($dispatcher->getUserOptions()->has('exceptionData')) {
            /** @var Collection $exceptionData */
            $exceptionData = $dispatcher->getUserOptions()->get('exceptionData');
            return $exceptionData->get('message', $unknown);
        }
        return $unknown;
    }

    public function notFoundAction()
    {
        Output::error($this->_getExceptionMessage());
    }

    public function internalServerErrorAction()
    {
        Output::error(sprintf('Internal Server Error. %s', $this->_getExceptionMessage()));
    }

    public function badRequestAction()
    {
        Output::error(sprintf('Bad Request. %s', $this->_getExceptionMessage()));
    }

    public function unknownErrorAction(): void
    {
        $this->dispatcher->forward([
            'task' => 'error',
            'action' => 'internalServerError',
        ]);
    }
}
