<?php

namespace Library\Models\Behavior;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\{ Behavior, Exception };

class AutoTimeable extends Behavior
{
    /**
     * @param string $type
     * @param ModelInterface|Model $model
     * @throws Exception
     */
    public function notify(string $type, ModelInterface $model): void
    {
        switch ($type) {
            case 'beforeValidationOnCreate':
                $options = $this->getOptions($type);
                if (! isset($options['field'])) {
                    throw new Exception('The option \'field\' is required');
                }
                $metaData = $model->getModelsMetaData();
                /** @noinspection NotOptimalIfConditionsInspection */
                if ($metaData->hasAttribute($model, $options['field']) && $model->{$options['field']} === null) {
                    $model->writeAttribute($options['field'], new RawValue('NOW()'));
                }
                break;
            case 'beforeValidationOnUpdate':
                $options = $this->getOptions($type);
                if (! isset($options['field'])) {
                    throw new Exception('The option \'field\' is required');
                }
                $metaData = $model->getModelsMetaData();
                if ($metaData->hasAttribute($model, $options['field'])) {
                    $model->writeAttribute($options['field'], new RawValue('NOW()'));
                }
                break;
            default:
                break;
        }
    }
}
