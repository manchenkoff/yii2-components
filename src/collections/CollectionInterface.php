<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\collections;

/**
 * CollectionInterface to implement BaseCollection class
 */
interface CollectionInterface
{
    /**
     * BaseCollection constructor.
     *
     * @param array $elements
     * @param string $className
     */
    function __construct(array $elements, string $className);

    /**
     * Adds a new item with the same class
     *
     * @param $element
     *
     * @return static
     */
    function add($element);

    /**
     * Removes an item by index
     *
     * @param int $index
     *
     * @return static
     */
    function remove(int $index);

    /**
     * Gets attribute values of each item
     *
     * @param string $attributeName
     *
     * @return array
     */
    function attribute(string $attributeName);

    /**
     * Returns a string with imploded collection items by attribute
     *
     * @param string $attribute
     * @param string $delimiter
     *
     * @return string
     */
    function implode(string $attribute, string $delimiter = ',');

    /**
     * Calculates the sum of each item attribute
     *
     * @param string $attributeName
     *
     * @return mixed
     */
    function sum(string $attributeName);

    /**
     * Returns a simple PHP array
     * @return array
     */
    function all();

    /**
     * Resets an array values to empty
     * @return static
     */
    function clear();

    /**
     * Applies a callback function to filter current collection
     *
     * @param callable $callback
     *
     * @return mixed
     */
    function filter(callable $callback);

    /**
     * Changes a collection order
     * @return static
     */
    function reverse();

    /**
     * Shuffle an array
     * @return static
     */
    function shuffle();

    /**
     * Get the slice of array values
     *
     * @param $offset
     * @param null $length
     * @param bool $preserveKeys
     *
     * @return mixed
     */
    function slice($offset, $length = null, $preserveKeys = false);

    /**
     * Applies callback function to each collection item
     *
     * @param callable $callback
     *
     * @return static
     */
    function walk(callable $callback);

    /**
     * Returns an array with results of callback function on each collection item
     *
     * @param callable $callback
     *
     * @return array
     */
    function map(callable $callback);

    /**
     * Find item with specified `attribute => value` in the collection
     *
     * @param string $attribute
     * @param $value
     *
     * @return int
     */
    function find(string $attribute, $value);

    /**
     * Checks if an item is exists in the collection
     *
     * @param $element
     *
     * @return bool
     */
    function contains($element);

    /**
     * Split a collection into array chunks
     *
     * @param int $size
     * @param bool $preserveKeys
     *
     * @return array
     */
    function split(int $size, bool $preserveKeys);

    /**
     * Checks if item exists by callback filter function
     *
     * @param callable $callback
     *
     * @return bool
     */
    function exists(callable $callback);
}