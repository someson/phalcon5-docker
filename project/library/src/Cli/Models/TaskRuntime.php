<?php

namespace Library\Cli\Models;

use Phalcon\Db\RawValue;

class TaskRuntime extends Base\TaskRuntime
{
    public function beforeValidationOnCreate(): void
    {
        $this->create_time = new RawValue('NOW()');
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->setConnectionService('dbCli');

        self::setup(['castOnHydrate' => true]);
        self::setup(['ignoreUnknownColumns' => true]);

        $this->useDynamicUpdate(true);
    }
}
