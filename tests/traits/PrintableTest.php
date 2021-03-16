<?php

declare(strict_types=1);

namespace tests\traits;

use manchenkov\yii\traits\Printable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PrintableTest extends TestCase
{
    public function testToString(): void
    {
        $className = 'PrintableMockClassName';

        /** @var Printable|MockObject $objectWithTrait */
        $objectWithTrait = $this->getMockForTrait(
            Printable::class,
            [],
            $className,
        );

        $actualString = (string) $objectWithTrait;

        self::assertEquals("{$className}: []\n", $actualString);
    }
}
