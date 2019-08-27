<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\collections\traits;

use yii\base\Arrayable;

/**
 * A trait with a few methods to convert an array
 * @property $elements
 */
trait ConvertibleTrait
{
    /**
     * Converts instance to a native PHP array.
     *
     * @param array $fields
     *
     * @return array
     */
    public function toArray(array $fields = [])
    {
        $result = [];

        foreach ($this->elements as $record) {
            /* @var $record Arrayable */
            $result[] = $record->toArray($fields);
        }

        return $result;
    }

    /**
     * Encodes array to a JSON string.
     *
     * @param array $fields Fields of model to convert
     * @param int $options The bit mask
     *
     * @return string The JSON string representation of array
     */
    public function toJson(array $fields = [], $options = 0)
    {
        $items = $this->toArray($fields);

        return json_encode($items, $options);
    }
}