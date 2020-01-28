<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\database;

use InvalidArgumentException;
use manchenkov\yii\collections\BaseCollection;
use manchenkov\yii\database\contracts\ActiveCollectionInterface;

class ActiveCollection extends BaseCollection implements ActiveCollectionInterface
{
    /**
     * @param $element
     */
    private function checkElementType($element)
    {
        if (!$element instanceof $this->_itemClass) {
            throw new InvalidArgumentException("All of the collection items must be an instance of the same class");
        }
    }

    /**
     * ActiveCollection constructor.
     *
     * @param array $elements
     * @param string $className
     */
    public function __construct(array $elements, string $className)
    {
        $this->_itemClass = $className;

        foreach ($elements as $element) {
            $this->checkElementType($element);
        }

        $this->elements = $elements;
    }

    public function add($element)
    {
        $this->checkElementType($element);

        $this->elements[] = $element;

        return $this;
    }

    public function remove(int $index)
    {
        $this->offsetUnset($index);

        return $this;
    }

    public function attribute(string $attributeName): array
    {
        return $this->map(
            function ($item) use ($attributeName) {
                return $item->{$attributeName};
            }
        );
    }

    public function implode(string $attribute, string $delimiter = ','): string
    {
        return implode($delimiter, $this->attribute($attribute));
    }

    public function sum(string $attributeName)
    {
        return array_reduce(
            $this->elements,
            function ($total, $model) use ($attributeName) {
                return $total + $model->{$attributeName};
            },
            0
        );
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function clear()
    {
        $this->elements = [];

        return $this;
    }

    public function filter(callable $callback)
    {
        return new static(
            array_filter(
                $this->elements,
                $callback
            ),
            $this->_itemClass
        );
    }

    public function reverse()
    {
        $this->elements = array_reverse($this->elements);

        return $this;
    }

    public function shuffle()
    {
        shuffle($this->elements);

        return $this;
    }

    public function slice($offset, $length = null, $preserveKeys = false)
    {
        return new static(
            array_slice(
                $this->elements,
                $offset,
                $length,
                $preserveKeys
            ),
            $this->_itemClass
        );
    }

    public function walk(callable $callback)
    {
        array_walk($this->elements, $callback);

        return $this;
    }

    public function map(callable $callback): array
    {
        return array_map(
            $callback,
            $this->elements
        );
    }

    public function find(string $attribute, $value): int
    {
        $index = -1;

        foreach ($this->elements as $idx => $element) {
            if ($element->{$attribute} == $value) {
                $index = $idx;
                break;
            }
        }

        return $index;
    }

    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function split(int $size, bool $preserveKeys): array
    {
        return array_chunk($this->elements, $size, $preserveKeys);
    }

    public function exists(callable $callback): bool
    {
        $isExists = false;

        foreach ($this->elements as $item) {
            if ($callback($item)) {
                $isExists = true;
                break;
            }
        }

        return $isExists;
    }

    public function save(): bool
    {
        $completed = true;

        app()->db->beginTransaction();

        foreach ($this->elements as $item) {
            if (!$item->save()) {
                $completed = false;
                break;
            }
        }

        if ($completed) {
            app()->db->transaction->commit();
        } else {
            app()->db->transaction->rollBack();
        }

        return $completed;
    }

    public function delete(): bool
    {
        $completed = true;

        app()->db->beginTransaction();

        foreach ($this->elements as $item) {
            if (!$item->delete()) {
                $completed = false;
                break;
            }
        }

        if ($completed) {
            app()->db->transaction->commit();
        } else {
            app()->db->transaction->rollBack();
        }

        return $completed;
    }

    public function validate(): bool
    {
        $completed = true;

        foreach ($this->elements as $item) {
            if (!$item->validate()) {
                $completed = false;
                break;
            }
        }

        return $completed;
    }

    public function errors(): array
    {
        $errors = [];

        foreach ($this->elements as $element) {
            if ($element->errors) {
                $errors[$element->id] = $element->errors;
            }
        }

        return $errors;
    }
}