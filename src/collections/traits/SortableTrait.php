<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\collections\traits;

use yii\helpers\ArrayHelper;

/**
 * A trait with a few methods to custom an array sort
 * @property $elements
 */
trait SortableTrait
{
    /**
     * Sorts items by selected attribute and direction
     *
     * @param string $attribute
     * @param int $sortType
     *
     * @return static
     */
    public function sortBy(string $attribute, $sortType = SORT_ASC)
    {
        ArrayHelper::multisort($this->elements, $attribute, $sortType);

        return $this;
    }

    /**
     * Returns an item with a minimum value of specified attribute name
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function min(string $attribute)
    {
        return $this->sortBy($attribute, SORT_DESC)->first();
    }

    /**
     * Returns an item with a maximum value of specified attribute name
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function max(string $attribute)
    {
        return $this->sortBy($attribute, SORT_ASC)->first();
    }

    /**
     * Returns a new array of items grouped by attribute values
     *
     * @param string $attribute
     *
     * @return array
     */
    public function groupBy(string $attribute): array
    {
        $items = [];

        foreach ($this->elements as $element) {
            $items[$element->{$attribute}] = $element;
        }

        return $items;
    }
}