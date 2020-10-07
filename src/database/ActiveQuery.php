<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\database;

use manchenkov\yii\database\contracts\ActiveQueryInterface;
use yii\db\ActiveQuery as BaseActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * Custom application ActiveQuery class with ActiveCollection support
 *
 * Supports JSON columns search
 *
 * @see ActiveRecord
 */
class ActiveQuery extends BaseActiveQuery implements ActiveQueryInterface
{
    public function ids(string $column = 'id'): array
    {
        return $this->select($column)->column();
    }

    public function byID(int $id, string $column = 'id'): ActiveQuery
    {
        return $this->andWhere([$column => $id]);
    }

    public function oneOrFail()
    {
        $model = $this->one();

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

    public function jsonKeyExists(string $key): ActiveQuery
    {
        return $this->jsonWhere($key, 'NULL', 'IS NOT');
    }

    public function jsonWhere(string $key, $value, string $operator = '='): ActiveQuery
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

    public function all($db = null)
    {
        $data = parent::all($db);

        /** @var ActiveRecord $activeRecordClass */
        $activeRecordClass = $this->modelClass;

        return ($this->asArray)
            ? $data
            : $activeRecordClass::collection($data);
    }

    public function newest(string $column = 'created_at')
    {
        return $this->last($column);
    }

    public function last(string $column = 'created_at')
    {
        return $this->orderBy($column . ' desc')->one();
    }

    public function oldest(string $column = 'created_at')
    {
        return $this->first($column);
    }

    public function first(string $column = 'created_at')
    {
        return $this->orderBy($column . ' asc')->one();
    }
}