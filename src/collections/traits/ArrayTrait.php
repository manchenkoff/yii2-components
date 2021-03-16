<?php

declare(strict_types=1);

namespace manchenkov\yii\collections\traits;

use ArrayIterator;
use Traversable;

/**
 * A trait with basic array access methods
 *
 * @property array $elements
 */
trait ArrayTrait
{
    /**
     * Retrieve an external iterator
     *
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
        return array_key_exists($offset, $this->elements);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->elements[$offset] ?? null;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->elements[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->elements[$offset]);
    }

    /**
     * Count elements of an object
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Checks if array is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Checks if array is not empty
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }
}
