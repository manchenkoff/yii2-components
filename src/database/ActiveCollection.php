<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\database;

use manchenkov\yii\collections\BaseCollection;
use InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\Exception;

/**
 * ActiveCollection component to work with strict type set of items
 */
class ActiveCollection extends BaseCollection
{
    /**
     * @param $element
     */
    private function checkElementType($element)
    {
        if (!$element instanceof $this->_itemClass) {
            throw new InvalidArgumentException("All of the collection items must be an instance of the same class");
        }
    }

    /**
     * ActiveCollection constructor.
     *
     * @param array $elements
     * @param string $className
     */
    public function __construct(array $elements, string $className)
    {
        $this->_itemClass = $className;

        foreach ($elements as $element) {
            $this->checkElementType($element);
        }

        $this->elements = $elements;
    }

    /**
     * @param $element
     *
     * @return $this
     */
    public function add($element)
    {
        $this->checkElementType($element);

        $this->elements[] = $element;

        return $this;
    }

    /**
     * @param int $index
     *
     * @return $this
     */
    public function remove(int $index)
    {
        $this->offsetUnset($index);

        return $this;
    }

    /**
     * @param string $attributeName
     *
     * @return array
     */
    public function attribute(string $attributeName)
    {
        return $this->map(
            function ($item) use ($attributeName) {
                return $item->{$attributeName};
            }
        );
    }

    /**
     * @param string $attribute
     * @param string $delimiter
     *
     * @return string
     */
    public function implode(string $attribute, string $delimiter = ',')
    {
        return implode($delimiter, $this->attribute($attribute));
    }

    /**
     * @param string $attributeName
     *
     * @return mixed
     */
    public function sum(string $attributeName)
    {
        return array_reduce(
            $this->elements,
            function ($total, $model) use ($attributeName) {
                return $total + $model->{$attributeName};
            },
            0
        );
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->elements = [];

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return ActiveCollection
     */
    public function filter(callable $callback)
    {
        return new static(
            array_filter(
                $this->elements,
                $callback
            ),
            $this->_itemClass
        );
    }

    /**
     * @return $this
     */
    public function reverse()
    {
        $this->elements = array_reverse($this->elements);

        return $this;
    }

    /**
     * @return $this
     */
    public function shuffle()
    {
        shuffle($this->elements);

        return $this;
    }

    /**
     * @param $offset
     * @param null $length
     * @param bool $preserveKeys
     *
     * @return ActiveCollection
     */
    public function slice($offset, $length = null, $preserveKeys = false)
    {
        return new static(
            array_slice(
                $this->elements,
                $offset,
                $length,
                $preserveKeys
            ),
            $this->_itemClass
        );
    }

    /**
     * @param callable $callback
     *
     * @return $this|mixed
     */
    public function walk(callable $callback)
    {
        array_walk($this->elements, $callback);

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return array
     */
    public function map(callable $callback)
    {
        return array_map(
            $callback,
            $this->elements
        );
    }

    /**
     * @param string $attribute
     * @param $value
     *
     * @return int
     */
    public function find(string $attribute, $value)
    {
        $index = -1;

        foreach ($this->elements as $idx => $element) {
            if ($element->{$attribute} == $value) {
                $index = $idx;
                break;
            }
        }

        return $index;
    }

    /**
     * @param $element
     *
     * @return bool
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * @param int $size
     * @param bool $preserveKeys
     *
     * @return array|mixed
     */
    public function split(int $size, bool $preserveKeys)
    {
        return array_chunk($this->elements, $size, $preserveKeys);
    }

    /**
     * @param callable $callback
     *
     * @return bool
     */
    public function exists(callable $callback)
    {
        $isExists = false;

        foreach ($this->elements as $item) {
            if ($callback($item)) {
                $isExists = true;
                break;
            }
        }

        return $isExists;
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws Exception
     */
    public function save()
    {
        $completed = true;

        app()->db->beginTransaction();

        foreach ($this->elements as $item) {
            if (!$item->save()) {
                $completed = false;
                break;
            }
        }

        if ($completed) {
            app()->db->transaction->commit();
        } else {
            app()->db->transaction->rollBack();
        }

        return $completed;
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws Exception
     */
    public function delete()
    {
        $completed = true;

        app()->db->beginTransaction();

        foreach ($this->elements as $item) {
            if (!$item->delete()) {
                $completed = false;
                break;
            }
        }

        if ($completed) {
            app()->db->transaction->commit();
        } else {
            app()->db->transaction->rollBack();
        }

        return $completed;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $completed = true;

        foreach ($this->elements as $item) {
            if (!$item->validate()) {
                $completed = false;
                break;
            }
        }

        return $completed;
    }

    /**
     * @return array
     */
    public function errors()
    {
        $errors = [];

        foreach ($this->elements as $element) {
            if ($element->errors) {
                $errors[$element->id] = $element->errors;
            }
        }

        return $errors;
    }
}