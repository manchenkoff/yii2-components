<?php

declare(strict_types=1);

namespace tests\data;

use manchenkov\yii\data\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /**
     * @dataProvider formDataProvider
     *
     * @param bool $loadedResult
     * @param int $validateCallCount
     */
    public function testLoad(bool $loadedResult, int $validateCallCount): void
    {
        $formObject = $this->createPartialMock(Form::class, ['load', 'validate']);

        $formObject
            ->expects(self::once())
            ->method('load')
            ->willReturn($loadedResult);

        $formObject
            ->expects(self::exactly($validateCallCount))
            ->method('validate')
            ->willReturn($loadedResult);

        $formObject->check([]);
    }

    public function formDataProvider(): array
    {
        return [
            'form was not loaded' => [
                false,
                0,
            ],

            'form was loaded' => [
                true,
                1,
            ],
        ];
    }
}
