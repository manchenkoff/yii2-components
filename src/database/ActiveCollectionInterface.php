<?php

declare(strict_types=1);

namespace manchenkov\yii\database;

use manchenkov\yii\collections\CollectionInterface;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\Exception;

/**
 * ActiveCollection component to work with strict type set of items
 *
 * @method ActiveCollectionInterface add($element)
 * @method ActiveCollectionInterface merge(ActiveCollectionInterface $collection)
 * @method ActiveCollectionInterface remove(int $index)
 * @method ActiveRecord[] all()
 * @method ActiveCollectionInterface clear()
 * @method ActiveCollectionInterface filter(callable $callback)
 * @method ActiveCollectionInterface reverse()
 * @method ActiveCollectionInterface shuffle()
 * @method ActiveCollectionInterface slice(int $offset, ?int $length, bool $preserveKeys = false)
 * @method ActiveCollectionInterface walk(callable $callback)
 * @method ActiveCollectionInterface sortBy(string $attribute, int $sortType = SORT_ASC)
 * @method ActiveRecord min(string $attribute)
 * @method ActiveRecord max(string $attribute)
 */
interface ActiveCollectionInterface extends CollectionInterface
{
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

    public function validate(): bool;

    public function errors(): array;
}
