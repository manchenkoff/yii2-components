<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

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
    public function offsetExists($offset): bool
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
    public function offsetGet($offset): ?ActiveRecordInterface
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
     * Checks if array is not empty
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Checks if array is empty
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Count elements of an object
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }
}