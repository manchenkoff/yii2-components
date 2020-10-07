<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\database;

use manchenkov\yii\components\Printable;
use manchenkov\yii\database\contracts\ActiveCollectionInterface;
use manchenkov\yii\database\contracts\ActiveRecordInterface;
use yii\db\ActiveRecord as BaseActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Custom application ActiveRecord class wrapper with additional methods
 */
class ActiveRecord extends BaseActiveRecord implements ActiveRecordInterface
{
    use Printable;

    /**
     * Returns a new ActiveCollection instance with query results
     *
     * @param array $items
     *
     * @return ActiveCollectionInterface
     */
    public static function collection(array $items): ActiveCollectionInterface
    {
        return new ActiveCollection($items, static::class);
    }

    /**
     * The method with automatic exception throwing when not found a model
     *
     * @param $condition
     *
     * @return ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public static function findOrFail($condition): ?ActiveRecord
    {
        return static::find()->where($condition)->oneOrFail();
    }

    /**
     * Returns a custom ActiveQuery object to work with queries
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(static::class);
    }

    public function hasOne($class, $link = [])
    {
        /** @var $class ActiveRecord */
        if (empty($link)) {
            $table = static::tableName();
            $pk = current(static::primaryKey());

            $link = ["{$table}_{$pk}" => $pk];
        }

        return parent::hasOne($class, $link);
    }

    public function hasMany($class, $link = [])
    {
        /** @var $class ActiveRecord */
        if (empty($link)) {
            $table = static::tableName();
            $pk = current(static::primaryKey());

            $link = ["{$table}_{$pk}" => $pk];
        }

        return parent::hasMany($class, $link);
    }

    public function belongsTo($class, $link = [])
    {
        /** @var $class ActiveRecord */
        if (empty($link)) {
            $table = $class::tableName();
            $pk = current($class::primaryKey());

            $link = [$pk => "{$table}_{$pk}"];
        }

        return parent::hasOne($class, $link);
    }
}