<?php

declare(strict_types=1);

namespace manchenkov\yii\database;

use yii\db\ActiveRecordInterface as BaseActiveRecordInterface;

interface ActiveRecordInterface extends BaseActiveRecordInterface
{
    /**
     * ActiveRecord `hasOne` method with automatic resolve columns names
     *
     * @param $class
     * @param array $link
     *
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function hasOne($class, $link = []);

    /**
     * ActiveRecord `hasMany` method with automatic resolve columns names
     *
     * @param $class
     * @param array $link
     *
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function hasMany($class, $link = []);

    /**
     * ActiveRecord `hasOne` reverse-method
     *
     * @param $class
     * @param array $link
     *
     * @return ActiveQuery|\yii\db\ActiveQuery
     */
    public function belongsTo($class, $link = []);
}
