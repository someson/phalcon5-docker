<?php

namespace Library\Cli\Models\Base;

class Task extends \Phalcon\Mvc\Model
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
    public $script_name;

    /**
     *
     * @var string
     */
    public $task_name;

    /**
     *
     * @var string
     */
    public $action_name;

    /**
     *
     * @var string
     */
    public $params;

    /**
     *
     * @var string
     */
    public $server_name;

    /**
     *
     * @var string
     */
    public $server_user;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var integer
     */
    public $exit_status;

    /**
     *
     * @var string
     */
    public $stdout;

    /**
     *
     * @var string
     */
    public $stderr;

    /**
     *
     * @var integer
     */
    public $pid;

    /**
     *
     * @var string
     */
    public $start_time;

    /**
     *
     * @var string
     */
    public $stop_time;

    public function initialize()
    {
        $this->setSchema(env('CLI_MYSQL_DATABASE'));
        $this->setSource("task");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Task[]|Task|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Task|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
