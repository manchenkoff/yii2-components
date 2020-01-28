<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2020
 */

declare(strict_types=1);

namespace manchenkov\yii\database\contracts;

use manchenkov\yii\database\ActiveQuery;
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