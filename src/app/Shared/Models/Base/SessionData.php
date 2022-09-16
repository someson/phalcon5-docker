<?php

namespace App\Shared\Models\Base;

class SessionData extends \Library\Models\ModelBase
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $data;

    /**
     *
     * @var integer
     */
    public $created_on;

    /**
     *
     * @var integer
     */
    public $modified_on;


    public function initialize()
    {
        $this->setSchema(env('MYSQL_DATABASE'));
        $this->setSource('session_data');
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SessionData[]|SessionData|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SessionData|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
