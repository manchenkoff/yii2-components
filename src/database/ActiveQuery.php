<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\database;

use yii\db\ActiveQuery as AQ;
use yii\web\NotFoundHttpException;

/**
 * Custom application ActiveQuery class with ActiveCollection support
 *
 * Supports JSON columns search
 *
 * @see ActiveRecord
 */
class ActiveQuery extends AQ
{
    /**
     * Gets IDs of result records
     *
     * @param string $column
     *
     * @return array
     */
    public function ids(string $column = 'id')
    {
        return $this->select($column)->column();
    }

    /**
     * Filter by ID column
     *
     * @param int $id
     *
     * @param string $column
     *
     * @return ActiveQuery
     */
    public function byID(int $id, string $column = 'id')
    {
        return $this->andWhere([$column => $id]);
    }

    /**
     * Throws NotFound error if no such model were found
     * @return array|null|ActiveRecord
     * @throws NotFoundHttpException
     */
    public function oneOrFail()
    {
        $model = $this->one();

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Checks if JSON key contains in a column value
     *
     * Usage: $query->jsonKeyExists('table_column.some.json.key')
     *
     * @param $key
     *
     * @return ActiveQuery
     */
    public function jsonKeyExists(string $key)
    {
        return $this->jsonWhere($key, 'NULL', 'IS NOT');
    }

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
     * @return ActiveQuery
     */
    public function jsonWhere(string $key, $value, string $operator = '=')
    {
        $keys = explode('.', $key);

        $column = array_shift($keys);
        $key = implode('.', $keys);

        if (is_array($value) && $operator == '=') {
            $operator = 'in';
            $value = "(" . implode(',', $value) . ")";
        }

        if (is_integer($key)) {
            $key = "$[{$key}]";
        } else {
            $key = "$.{$key}";
        }

        // column->>'$[0]' = value
        // column->>'$.key' = value
        // column->>'$.key' > value
        // column->>'$.key' in (value1, value2, value3)

        $expression = "{$column}->>'{$key}' {$operator} {$value}";

        return $this->andWhere($expression);
    }

    /**
     * Creates a new ActiveCollection instance with query results
     *
     * @param array $records
     *
     * @return ActiveCollection
     */
    protected function collection(array $records)
    {
        return new ActiveCollection($records, $this->modelClass);
    }

    /**
     * Returns result of a query set
     *
     * @param null $db
     *
     * @return ActiveCollection|array|\yii\db\ActiveRecord[]
     */
    public function all($db = null)
    {
        $data = parent::all($db);

        return ($this->asArray) ? $data : $this->collection($data);
    }

    /**
     * Returns the first record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    protected function first(string $column = 'created_at')
    {
        return $this->orderBy($column . ' asc')->one();
    }

    /**
     * Returns the last record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    protected function last(string $column = 'created_at')
    {
        return $this->orderBy($column . ' desc')->one();
    }

    /**
     * Returns the last record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    protected function newest(string $column = 'created_at')
    {
        return $this->last($column);
    }

    /**
     * Returns the first record sorted by column name
     *
     * @param string $column
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    protected function oldest(string $column = 'created_at')
    {
        return $this->first($column);
    }
}