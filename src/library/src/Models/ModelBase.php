<?php

namespace Library\Models;

use Phalcon\Di\Di;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Db\{ Column, RawValue };
use Phalcon\Messages\Message;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Query\{ Builder, BuilderInterface };

class ModelBase extends Model
{
    public function onConstruct()
    {
        $this->useDynamicUpdate(true);

        // These Behaviors (if any) are notified AFTER native behavior methods in the models
        $this->addBehavior(new Behavior\AutoTimeable([
            'beforeValidationOnCreate' => ['field' => 'created_on'],
            'beforeValidationOnUpdate' => ['field' => 'updated_on'],
        ]));

        $metaData = $this->getModelsMetaData();
        if ($metaData->hasAttribute($this, 'deleted')) {
            $this->addBehavior(new SoftDelete([
                'field' => 'deleted',
                'value' => 1
            ]));
        }
    }

    /**
     * Redefines result of getChangedFields for the case if orm.cast_on_hydrate is ON
     * @return array
     */
    public function getChangedFields(): array
    {
        $changedFields = parent::getChangedFields();

        $snapshot = $this->getSnapshotData();
        $dataTypes = $this->getModelsMetaData()->getDataTypes($this);

        foreach ($changedFields as $i => $field) {
            switch ($dataTypes[$field]) {
                case Column::TYPE_INTEGER :
                    if (!$this->$field instanceof RawValue) {
                        $this->$field = (int) $this->$field;
                    }
                    break;
                case Column::TYPE_DECIMAL:
                case Column::TYPE_DOUBLE:
                case Column::TYPE_FLOAT:
                $this->$field = (float) $this->$field;
                    break;
                default:
                    break;
            }
            if ($snapshot[$field] === $this->$field) {
                unset($changedFields[$i]);
            }
        }
        return $changedFields;
    }

    /**
     * Making possible using of thrown error messages
     * @param array $params
     * @return bool
     */
    public function catchableSave(array $params = []): bool
    {
        $success = false;
        try {
            $this->assign($params);
            $success = $this->save();
        } catch (\Exception $e) {
            $this->appendMessage(new Message($e->getMessage()));
        }
        return $success;
    }

    /**
     * Making possible using of thrown error messages
     * @return bool
     */
    public function catchableDelete(): bool
    {
        $success = false;
        try {
            $success = $this->delete();
        } catch (\Exception $e) {
            $this->appendMessage(new Message($e->getMessage()));
        }
        return $success;
    }

    protected static function getCacheKey($calledMethod, $argsHash = ''): string
    {
        return sprintf('%s_%s(%s)', str_replace('\\', '_', static::class), $calledMethod, $argsHash);
    }

    public static function getConnection(): AdapterInterface
    {
        return Di::getDefault()->getShared('db');
    }

    public static function modelsManager(): Manager
    {
        return Di::getDefault()->getShared('modelsManager');
    }

    /**
     * @return BuilderInterface|Builder
     */
    public static function buildQuery(): BuilderInterface
    {
        return self::modelsManager()->createBuilder();
    }

    public function hasAttribute(string $attr): bool
    {
        return $this->getModelsMetaData()->hasAttribute($this, $attr);
    }
}
