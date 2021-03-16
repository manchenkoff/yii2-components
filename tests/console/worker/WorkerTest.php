<?php

declare(strict_types=1);

namespace tests\console\worker;

use manchenkov\yii\console\worker\Worker;
use PHPUnit\Framework\TestCase;

class WorkerTest extends TestCase
{
    public function testActionRun(): void
    {
        $workerObject = $this->createPartialMock(
            Worker::class,
            ['handle', 'isEnabled', 'getWaitingTime']
        );

        $workerObject
            ->expects(self::once())
            ->method('handle');

        $workerObject
            ->expects(self::once())
            ->method('getWaitingTime')
            ->willReturn('1');

        $workerObject
            ->expects(self::exactly(2))
            ->method('isEnabled')
            ->willReturnOnConsecutiveCalls(...[true, false]);

        $workerObject->actionRun();
    }
}
