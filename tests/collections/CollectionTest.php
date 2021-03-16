<?php

declare(strict_types=1);

namespace tests\collections;

use ArrayIterator;
use manchenkov\yii\collections\Collection;
use PHPUnit\Framework\TestCase;
use tests\classes\ExampleElement;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class CollectionTest extends TestCase
{
    public function testCreateNewCollection(): void
    {
        $objects = [new ExampleElement('one'), new ExampleElement('two')];

        $collection = new Collection($objects, ExampleElement::class);

        self::assertNotEmpty($collection->all(), 'Collection does not have elements');
    }

    public function testCreateNewCollectionWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $objects = [new ExampleElement('one'), new Model()];

        new Collection($objects, ExampleElement::class);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testContains(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $searchElement = clone $elements[0];

        self::assertTrue($collection->contains($searchElement));
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testRemove(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->remove(0);

        self::assertCount(1, $collection);
        self::assertEquals($elements[1], ...$collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testSplit(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $chunks = $collection->split(1);

        self::assertCount(2, $chunks);

        self::assertEquals($elements[0], ...$chunks[0]);
        self::assertEquals($elements[1], ...$chunks[1]);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testSum(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $actualResultByAttribute = $collection->sum('number');
        $actualResultByMethod = $collection->sum('getNumber');

        $expectedResult = 30;

        self::assertEquals($expectedResult, $actualResultByAttribute);
        self::assertEquals($expectedResult, $actualResultByMethod);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testOffsetSet(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->offsetSet(0, $elements[1]);

        self::assertEquals([$elements[1], $elements[1]], $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testUnshift(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $item = new ExampleElement('new one');

        $collection->unshift($item);

        self::assertCount(3, $collection->all());
        self::assertEquals($item, $collection[0]);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testMin(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        /** @var ExampleElement $actualResult */
        $actualResult = $collection->min('number');

        self::assertEquals(10, $actualResult->number);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testLast(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        /** @var ExampleElement $actualResult */
        $actualResult = $collection->last();

        self::assertEquals(end($elements), $actualResult);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testMap(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $actualResult = $collection->map(
            static function (ExampleElement $element) {
                return $element->number + 10;
            }
        );

        self::assertEquals([20, 30], $actualResult);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testPush(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $item = new ExampleElement('new one');

        $collection->push($item);

        self::assertEquals($collection->last(), $item);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testClear(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->clear();

        self::assertEmpty($collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testShift(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $firstItem = $collection->shift();

        self::assertEquals($elements[0], $firstItem);
        self::assertCount(count($elements) - 1, $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testSortBy(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->sortBy('value', SORT_DESC);

        $expected = [
            $elements[1],
            $elements[0],
        ];

        self::assertEquals($expected, $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testGroupBy(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $groups = $collection->groupBy('value');

        $expected = [
            'one' => [
                $elements[0],
            ],
            'two' => [
                $elements[1],
            ],
        ];

        self::assertEquals($expected, $groups);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testGetIterator(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $iterator = $collection->getIterator();

        self::assertInstanceOf(ArrayIterator::class, $iterator);
        self::assertEquals($collection->count(), $iterator->count());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testOffsetExists(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $actualResult = $collection->offsetExists(9999);

        self::assertFalse($actualResult);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testImplode(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $implodedValues = $collection->implode('value');

        self::assertEquals('one,two', $implodedValues);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testFind(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $actualResult = $collection->find('getValue', 'two');

        self::assertEquals(1, $actualResult);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testEnd(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals(end($elements), $collection->end());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testExists(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $actualResult = $collection->exists(
            static function (ExampleElement $element) {
                return $element->getValue() === 'two';
            }
        );

        self::assertTrue($actualResult);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testFirst(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals(reset($elements), $collection->first());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testSlice(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $slice = $collection->slice(1, 1)->all();

        self::assertSame(array_slice($elements, 1, 1), $slice);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testKey(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals(0, $collection->key());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testIsEmpty(array $elements): void
    {
        $collectionEmpty = new Collection([], ExampleElement::class);
        $collectionWithItems = new Collection($elements, ExampleElement::class);

        self::assertTrue($collectionEmpty->isEmpty());
        self::assertFalse($collectionWithItems->isEmpty());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testPop(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals(end($elements), $collection->pop());
        self::assertCount(count($elements) - 1, $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testAll(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertSame($elements, $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testOffsetUnset(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->offsetUnset(0);

        self::assertCount(count($elements) - 1, $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testAttribute(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals([10, 20], $collection->attribute('number'));
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testPrevious(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->next();

        self::assertEquals($collection[0], $collection->previous());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testCount(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals(count($elements), $collection->count());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testNext(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals($collection[1], $collection->next());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testFilter(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->filter(
            static function (ExampleElement $element) {
                return $element->getNumber() > 15;
            }
        );

        self::assertCount(1, $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testIsNotEmpty(array $elements): void
    {
        $collectionEmpty = new Collection([], ExampleElement::class);
        $collectionWithItems = new Collection($elements, ExampleElement::class);

        self::assertTrue($collectionWithItems->isNotEmpty());
        self::assertFalse($collectionEmpty->isNotEmpty());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testCurrent(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        self::assertEquals($collection[0], $collection->current());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testWalk(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->walk(
            static function (ExampleElement $element) {
                ++$element->number;
            }
        );

        $actualValues = $collection->attribute('number');

        self::assertEquals([11, 21], $actualValues);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testMax(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        /** @var ExampleElement $actualResult */
        $actualResult = $collection->max('number');

        self::assertEquals(20, $actualResult->number);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testOffsetGet(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $actualResult = $collection->offsetGet(0);

        self::assertEquals($elements[0], $actualResult);
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testReverse(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $collection->reverse();

        self::assertSame(array_reverse($elements), $collection->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testAdd(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $item = new ExampleElement('new one');

        $collection->add($item);

        self::assertTrue($collection->contains($item));
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testMerge(array $elements): void
    {
        $collectionSource = new Collection($elements, ExampleElement::class);
        $collectionCopy = new Collection($elements, ExampleElement::class);

        $collectionSource->merge($collectionCopy);

        self::assertSame(array_merge($elements, $elements), $collectionSource->all());
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @param array $elements
     */
    public function testReset(array $elements): void
    {
        $collection = new Collection($elements, ExampleElement::class);

        $first = $collection->first();
        $last = $collection->last();

        self::assertNotEquals($first, $last);

        $last = $collection->reset();

        self::assertEquals($first, $last);
    }

    /**
     * @return ExampleElement[]
     */
    public function elementsDataProvider(): array
    {
        return [
            'items' => [
                [
                    new ExampleElement('one', 10),
                    new ExampleElement('two', 20),
                ],
            ],
        ];
    }
}
