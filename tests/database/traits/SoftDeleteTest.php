<?php

declare(strict_types=1);

namespace tests\database\traits;

use PHPUnit\Framework\TestCase;
use tests\classes\SoftDeleteDto;

class SoftDeleteTest extends TestCase
{
    public function testRestore(): void
    {
        $record = new SoftDeleteDto();

        $record->deleted_at = time();

        self::assertTrue($record->getIsDeleted());

        $record->restore();

        self::assertFalse($record->getIsDeleted());
    }

    public function testDestroy(): void
    {
        $record = new SoftDeleteDto();

        self::assertTrue($record->destroy());
    }

    public function testGetIsDeleted(): void
    {
        $record = new SoftDeleteDto();

        self::assertFalse($record->getIsDeleted());

        $record->deleted_at = time();

        self::assertTrue($record->getIsDeleted());
    }

    public function testDelete(): void
    {
        $record = new SoftDeleteDto();

        self::assertFalse($record->getIsDeleted());

        $record->delete();

        self::assertTrue($record->getIsDeleted());
    }
}
