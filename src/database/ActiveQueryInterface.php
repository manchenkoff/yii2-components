<?php

declare(strict_types=1);

namespace manchenkov\yii\database;

use yii\db\ActiveQueryInterface as BaseActiveQueryInterface;
use yii\web\NotFoundHttpException;

/**
 * @method one()
 */
interface ActiveQueryInterface extends BaseActiveQueryInterface
{
    /**
     * Gets IDs of result records
     *
     * @param string $column
     *
     * @return array
     */
    public function ids(string $column = 'id'): array;

    /**
     * Filter by ID column
     *
     * @param int $id
     *
     * @param string $column
     *
     * @return static
     */
    public function byID(int $id, string $column = 'id'): ActiveQueryInterface;

    /**
     * Throws NotFound error if no such model were found
     * @return array|null|ActiveRecord
     * @throws NotFoundHttpException
     */
    public function oneOrFail();

    /**
     * Checks if JSON key contains in a column value
     *
     * Usage: $query->jsonKeyExists('table_column.some.json.key')
     *
     * @param $key
     *
     * @return static
     */
    public function jsonKeyExists(string $key): ActiveQueryInterface;

    /**
     * Search method for JSON columns
     *
     * Usage:
     *  $query->jsonWhere('table_column.some.json.key', 'equals_value')
     *  $query->jsonWhere('table_column.some.json.key', 'lower_value', '>')
     *
     * @param string $key
     * @param $value
     * @param string $operator
     *
     * @return static
     */
    public function jsonWhere(string $key, $value, string $operator = '='): ActiveQueryInterface;

    /**
     * Returns result of a query set
     *
     * @param null $db
     *
     * @return ActiveCollection|array|\yii\db\ActiveRecord[]|ActiveRecord[]
     */
    public function all($db = null);

    /**
     * Returns the first record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|ActiveRecord|null
     */
    public function first(string $column = 'created_at');

    /**
     * Returns the last record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|ActiveRecord|null
     */
    public function last(string $column = 'created_at');

    /**
     * Returns the last record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|ActiveRecord|null
     */
    public function newest(string $column = 'created_at');

    /**
     * Returns the first record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|ActiveRecord|null
     */
    public function oldest(string $column = 'created_at');
}
