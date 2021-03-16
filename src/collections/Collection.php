<?php

declare(strict_types=1);

namespace manchenkov\yii\collections;

use Arrayzy\Traits\DoubleEndedQueueTrait;
use Arrayzy\Traits\TraversableTrait;
use manchenkov\yii\collections\traits\ArrayTrait;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

class Collection implements CollectionInterface
{
    use TraversableTrait;
    use DoubleEndedQueueTrait;
    use ArrayTrait;

    protected array $elements;
    protected string $className;

    public function __construct(array $elements, string $className)
    {
        $this->className = $className;

        foreach ($elements as $element) {
            $this->checkElementType($element);
        }

        $this->elements = $elements;
    }

    public function add($element): CollectionInterface
    {
        $this->checkElementType($element);

        $this->elements[] = $element;

        return $this;
    }

    public function merge(CollectionInterface $collection): CollectionInterface
    {
        $itemsToMerge = $collection->all();

        $this->checkElementType($itemsToMerge[0]);

        $this->elements = array_merge($this->elements, $itemsToMerge);

        return $this;
    }

    public function remove(int $index): CollectionInterface
    {
        unset($this->elements[$index]);

        return $this;
    }

    public function attribute(string $attributeName, bool $unique = false): array
    {
        $values = [];

        foreach ($this->elements as $element) {
            $values[] = $this->getObjectAttribute($element, $attributeName);
        }

        if ($unique) {
            $values = array_unique($values);
        }

        return $values;
    }

    public function implode(string $attribute, string $delimiter = ',', bool $unique = false): string
    {
        return implode($delimiter, $this->attribute($attribute, $unique));
    }

    public function sum(string $attributeName)
    {
        $values = $this->attribute($attributeName);

        return array_sum($values);
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function clear(): CollectionInterface
    {
        $this->elements = [];

        return $this;
    }

    public function filter(callable $callback): CollectionInterface
    {
        $this->elements = array_filter($this->elements, $callback);

        return $this;
    }

    public function reverse(): CollectionInterface
    {
        $this->elements = array_reverse($this->elements);

        return $this;
    }

    public function shuffle(): CollectionInterface
    {
        shuffle($this->elements);

        return $this;
    }

    public function slice(int $offset, ?int $length, bool $preserveKeys = false): CollectionInterface
    {
        $arraySlice = array_slice(
            $this->elements,
            $offset,
            $length,
            $preserveKeys
        );

        return new static($arraySlice, $this->className);
    }

    public function walk(callable $callback): CollectionInterface
    {
        array_walk($this->elements, $callback);

        return $this;
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->elements);
    }

    public function find(string $attribute, $value): int
    {
        foreach ($this->elements as $idx => $element) {
            if ($this->getObjectAttribute($element, $attribute) === $value) {
                return $idx;
            }
        }

        return -1;
    }

    public function contains($element): bool
    {
        foreach ($this->elements as $e) {
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($e == $element) {
                return true;
            }
        }

        return false;
    }

    public function split(int $size): array
    {
        return array_chunk($this->elements, $size);
    }

    public function exists(callable $callback): bool
    {
        foreach ($this->elements as $element) {
            $exists = $callback($element);

            if ($exists === true) {
                return true;
            }
        }

        return false;
    }

    public function sortBy(string $attribute, int $sortType = SORT_ASC): CollectionInterface
    {
        ArrayHelper::multisort($this->elements, $attribute, $sortType);

        return $this;
    }

    public function min(string $attribute): object
    {
        /** @var Collection $collection */
        $collection = (clone $this)->sortBy($attribute);

        return $collection->first();
    }

    public function max(string $attribute): object
    {
        /** @var Collection $collection */
        $collection = (clone $this)->sortBy($attribute, SORT_DESC);

        return $collection->first();
    }

    public function groupBy(string $attribute): array
    {
        $groups = [];

        foreach ($this->elements as $element) {
            $value = $this->getObjectAttribute($element, $attribute);
            $groups[$value][] = $element;
        }

        return $groups;
    }

    protected function checkElementType($element): void
    {
        if (!$element instanceof $this->className) {
            throw new InvalidArgumentException("All of the collection items must be an instance of the same class");
        }
    }

    private function getObjectAttribute($element, $attributeName)
    {
        return method_exists($element, $attributeName)
            ? $element->{$attributeName}()
            : $element->{$attributeName};
    }
}
