<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\database;

use manchenkov\yii\components\Printable;
use yii\db\ActiveRecord as AR;
use yii\web\NotFoundHttpException;

/**
 * Custom application ActiveRecord class wrapper with additional methods
 */
class ActiveRecord extends AR
{
    use Printable;

    /**
     * The method with automatic exception throwing when not found a model
     *
     * @param $condition
     *
     * @return ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public static function findOrFail($condition)
    {
        return static::find()->where($condition)->oneOrFail();
    }

    /**
     * Returns a custom ActiveQuery object to work with queries
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        $query = new ActiveQuery(static::class);

        return $query;
    }

    /**
     * ActiveRecord `hasOne` method with automatic resolve columns names
     *
     * @param $class
     * @param array $link
     *
     * @return \yii\db\ActiveQuery
     */
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

    /**
     * ActiveRecord `hasOne` reverse-method
     *
     * @param $class
     * @param array $link
     *
     * @return \yii\db\ActiveQuery
     */
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