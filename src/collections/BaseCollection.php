<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\collections;

use ArrayAccess;
use Arrayzy\Interfaces\DoubleEndedQueueInterface;
use Arrayzy\Traits\DoubleEndedQueueTrait;
use Arrayzy\Traits\TraversableTrait;
use Countable;
use IteratorAggregate;
use manchenkov\yii\collections\contracts\CollectionInterface;
use manchenkov\yii\collections\traits\ArrayTrait;
use manchenkov\yii\collections\traits\ConvertibleTrait;
use manchenkov\yii\collections\traits\SortableTrait;

/**
 * Abstract BaseCollection class to manage elements with improved functionality
 */
abstract class BaseCollection implements
    DoubleEndedQueueInterface,
    IteratorAggregate,
    ArrayAccess,
    Countable,
    CollectionInterface
{
    /**
     * @var array Array of items
     */
    protected $elements;

    /**
     * @var string Specific class to determine item's type
     */
    protected $_itemClass;

    /**
     * Include some helpful traits
     */
    use TraversableTrait,
        ConvertibleTrait,
        DoubleEndedQueueTrait,
        SortableTrait,
        ArrayTrait;
}