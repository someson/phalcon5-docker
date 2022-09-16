<?php

namespace Library\Models\Behavior;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\Behavior;

class Jsonable extends Behavior
{
    /**
     * @param string $type
     * @param ModelInterface|Model $model
     * @throws \JsonException
     */
    public function notify(string $type, ModelInterface|Model $model): void
    {
        /** @var Model $model */
        $options = $this->getOptions();
        $field = $options['field'];
        $jsonOptions = $options['jsonOptions'] ?? JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT;
        switch ($type) {
            case 'afterFetch':
            case 'afterSave':
                $model->{$field} = self::decodeField($model->{$field});
                break;
            case 'beforeSave':
                $value = self::encodeField($model->{$field}, $jsonOptions);
                $model->writeAttribute($field, $value);
                break;
            default:
                break;
        }
    }

    public static function encodeField($field, $jsonOptions = 0)
    {
        $field = (array) $field;
        return $field ? json_encode($field, JSON_THROW_ON_ERROR | $jsonOptions) : new RawValue('NULL');
    }

    public static function decodeField(string $field)
    {
        if ($field) {
            return json_decode($field, true, 512, JSON_THROW_ON_ERROR) ?? [];
        }
        return null;
    }
}
