<?php

namespace Library\Cli\Models;

use Phalcon\Db\RawValue;

class Task extends Base\Task
{
    public function beforeValidationOnCreate(): void
    {
        /** @var array $argv */
        $argv = $_SERVER['argv'];
        array_shift($argv);

        $this->server_name = php_uname('n');
        $this->server_user = get_current_user();
        $this->pid = getmypid();

        $params = [];
        foreach ($argv as $value) {
            if (strpos($value, '-') === false) {
                $params[] = $value;
            }
        }
        $this->params = implode(' ', $params);
        $this->start_time = new RawValue('NOW()');
    }

    public function beforeValidationOnUpdate(): void
    {
        $this->stop_time = new RawValue('NOW()');
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->setConnectionService('dbCli');

        self::setup(['castOnHydrate' => true]);
        self::setup(['ignoreUnknownColumns' => true]);

        $this->useDynamicUpdate(true);
        $this->skipAttributesOnUpdate(['script_name','pid']);
    }

    public function insertTask($scriptName, $task_name, $action_name)
    {
        $this->script_name = $scriptName;
        $this->task_name   = $task_name;
        $this->action_name = $action_name;

        return $this->save() ? $this->id : false;
    }

    public static function updateById(int $id, $stdout, $stderr, $status = 0): bool
    {
        $state = $status ? 'FAIL' : 'SUCCESS';

        if ($task = self::findFirst($id)) {
            $task->assign([
                'exit_status' => $status,
                'state' => $state,
                'stdout' => $stdout,
                'stderr' => $stderr,
            ]);
            if (! $task->update()) {
                trigger_error('Could not update the Task', E_USER_ERROR);
            }
        }
        return true;
    }
}
