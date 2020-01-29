<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2020
 */

declare(strict_types=1);

namespace manchenkov\yii\database\contracts;

use manchenkov\yii\database\ActiveCollection;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\Exception;

/**
 * ActiveCollection component to work with strict type set of items
 */
interface ActiveCollectionInterface
{
    /**
     * @param $element
     *
     * @return static
     */
    public function add($element);

    /**
     * @param int $index
     *
     * @return static
     */
    public function remove(int $index);

    /**
     * @param string $attributeName
     *
     * @return array
     */
    public function attribute(string $attributeName): array;

    /**
     * @param string $attribute
     * @param string $delimiter
     *
     * @return string
     */
    public function implode(string $attribute, string $delimiter = ','): string;

    /**
     * @param string $attributeName
     *
     * @return mixed
     */
    public function sum(string $attributeName);

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @return static
     */
    public function clear();

    /**
     * @param callable $callback
     *
     * @return ActiveCollection
     */
    public function filter(callable $callback);

    /**
     * @return static
     */
    public function reverse();

    /**
     * @return static
     */
    public function shuffle();

    /**
     * @param $offset
     * @param null $length
     * @param bool $preserveKeys
     *
     * @return ActiveCollection
     */
    public function slice($offset, $length = null, $preserveKeys = false);

    /**
     * @param callable $callback
     *
     * @return static|mixed
     */
    public function walk(callable $callback);

    /**
     * @param callable $callback
     *
     * @return array
     */
    public function map(callable $callback): array;

    /**
     * @param string $attribute
     * @param $value
     *
     * @return int
     */
    public function find(string $attribute, $value): int;

    /**
     * @param $element
     *
     * @return bool
     */
    public function contains($element): bool;

    /**
     * @param int $size
     * @param bool $preserveKeys
     *
     * @return array
     */
    public function split(int $size, bool $preserveKeys): array;

    /**
     * @param callable $callback
     *
     * @return bool
     */
    public function exists(callable $callback): bool;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws Exception
     */
    public function save(): bool;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws Exception
     */
    public function delete(): bool;

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return array
     */
    public function errors(): array;
}