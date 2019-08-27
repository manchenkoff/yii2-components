<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\collections\traits;

use ArrayIterator;
use Traversable;
use yii\db\ActiveRecordInterface;

/**
 * A trait with basic array access methods
 * @property $elements
 */
trait ArrayTrait
{
    /**
     * Retrieve an external iterator
     * @return ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     *
     * @return ActiveRecordInterface|mixed|null
     */
    public function offsetGet($offset)
    {
        return (isset($this->elements[$offset])) ? $this->elements[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }

    /**
     * Count elements of an object
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * Checks if array is empty
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() > 0;
    }

    /**
     * Checks if array is not empty
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }
}