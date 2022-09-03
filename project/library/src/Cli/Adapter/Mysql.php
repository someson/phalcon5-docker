<?php

namespace Library\Cli\Adapter;

use Library\Cli\AbstractAdapter;
use Phalcon\Mvc\Model;

class Mysql extends AbstractAdapter
{
    private string $_modelClass;

    public function __construct(string $modelClass)
    {
        $this->_modelClass = $modelClass;
    }

    /**
     * {@inheritDoc}
     */
    public function report(array $attr)
    {
        /** @var Model|string $modelClass */
        $modelClass = $this->_modelClass;
        if (! $lastId = $this->getOption('lastId')) {
            /** @var Model|\stdClass $entry */
            $entry = new $modelClass();

            $metadata = $entry->getModelsMetaData();
            $primaryKeys = $metadata->getPrimaryKeyAttributes($entry);
            $id = $primaryKeys[0];

            if ($entry->assign($attr)->save()) {
                return $entry->{$id};
            }
            return false;
        }
        if ($entry = $modelClass::findFirst($lastId)) {
            $entry->assign($attr);
            if (! $entry->update()) {
                trigger_error(
                    sprintf('[%s]: could not update the [task] entity', __CLASS__),
                    E_USER_ERROR
                );
            }
        }
        return true;
    }
}
