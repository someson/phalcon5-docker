<?php

namespace Library\Cli\Models\Base;

class TaskRuntime extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $file;

    /**
     *
     * @var integer
     */
    public $line;

    /**
     *
     * @var integer
     */
    public $error_type;

    /**
     *
     * @var string
     */
    public $server_name;

    /**
     *
     * @var string
     */
    public $execution_script;

    /**
     *
     * @var integer
     */
    public $pid;

    /**
     *
     * @var string
     */
    public $ip_address;

    /**
     *
     * @var string
     */
    public $create_time;

    public function initialize()
    {
        $this->setSchema(env('CLI_MYSQL_DATABASE'));
        $this->setSource("task_runtime");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TaskRuntime[]|TaskRuntime|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TaskRuntime|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
