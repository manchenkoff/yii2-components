<?php

declare(strict_types=1);

namespace manchenkov\yii\collections;

use ArrayAccess;
use Arrayzy\Interfaces\DoubleEndedQueueInterface;
use Countable;
use IteratorAggregate;

interface CollectionInterface extends DoubleEndedQueueInterface, IteratorAggregate, ArrayAccess, Countable
{
    /**
     * Adds a new item with the same class
     *
     * @param $element
     *
     * @return static
     */
    public function add($element): CollectionInterface;

    /**
     * Merge other collection into current one
     *
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function merge(CollectionInterface $collection): CollectionInterface;

    /**
     * Removes an item by index
     *
     * @param int $index
     *
     * @return static
     */
    public function remove(int $index): CollectionInterface;

    /**
     * Gets attribute values of each item
     *
     * @param string $attributeName
     * @param bool $unique
     *
     * @return array
     */
    public function attribute(string $attributeName, bool $unique = false): array;

    /**
     * Returns a string with imploded collection items by attribute
     *
     * @param string $attribute
     * @param string $delimiter
     * @param bool $unique
     *
     * @return string
     */
    public function implode(string $attribute, string $delimiter = ',', bool $unique = false): string;

    /**
     * Calculates the sum of each item attribute
     *
     * @param string $attributeName
     *
     * @return float|int
     */
    public function sum(string $attributeName);

    /**
     * Returns a simple PHP array
     *
     * @return array
     */
    public function all(): array;

    /**
     * Resets an array values to empty
     *
     * @return static
     */
    public function clear(): CollectionInterface;

    /**
     * Applies a callback function to filter current collection
     *
     * @param callable $callback
     *
     * @return static
     */
    public function filter(callable $callback): CollectionInterface;

    /**
     * Changes a collection order
     *
     * @return static
     */
    public function reverse(): CollectionInterface;

    /**
     * Shuffle an array
     *
     * @return static
     */
    public function shuffle(): CollectionInterface;

    /**
     * Get the slice of array values
     *
     * @param int $offset
     * @param int|null $length
     * @param bool $preserveKeys
     *
     * @return CollectionInterface
     */
    public function slice(int $offset, ?int $length, bool $preserveKeys = false): CollectionInterface;

    /**
     * Applies callback function to each collection item
     *
     * @param callable $callback
     *
     * @return static
     */
    public function walk(callable $callback): CollectionInterface;

    /**
     * Returns an array with results of callback function on each collection item
     *
     * @param callable $callback
     *
     * @return array
     */
    public function map(callable $callback): array;

    /**
     * Find item with specified `attribute => value` in the collection
     *
     * @param string $attribute
     * @param $value
     *
     * @return int
     */
    public function find(string $attribute, $value): int;

    /**
     * Checks if an item is exists in the collection
     *
     * @param $element
     *
     * @return bool
     */
    public function contains($element): bool;

    /**
     * Split a collection into array chunks
     *
     * @param int $size
     *
     * @return array
     */
    public function split(int $size): array;

    /**
     * Checks if item exists by callback filter function
     *
     * @param callable $callback
     *
     * @return bool
     */
    public function exists(callable $callback): bool;

    /**
     * Checks if array is empty
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Checks if array is not empty
     *
     * @return bool
     */
    public function isNotEmpty(): bool;

    /**
     * Sorts items by selected attribute and direction
     *
     * @param string $attribute
     * @param int $sortType
     *
     * @return CollectionInterface
     */
    public function sortBy(string $attribute, int $sortType = SORT_ASC): CollectionInterface;

    /**
     * Returns an item with a minimum value of specified attribute name
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function min(string $attribute): object;

    /**
     * Returns an item with a maximum value of specified attribute name
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function max(string $attribute): object;

    /**
     * Returns a new array of items grouped by attribute values
     *
     * @param string $attribute
     *
     * @return array
     */
    public function groupBy(string $attribute): array;
}
