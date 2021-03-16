<?php

declare(strict_types=1);

namespace tests\console\command;

use manchenkov\yii\console\command\Command;
use PHPUnit\Framework\TestCase;
use yii\helpers\Console;

class CommandTest extends TestCase
{
    /**
     * @dataProvider outputDataProvider
     *
     * @param string $message
     * @param string $method
     * @param string $expectedString
     * @param int $expectedColor
     */
    public function testColoredOutput(string $message, string $method, string $expectedString, int $expectedColor): void
    {
        $commandObject = $this->createPartialMock(Command::class, ['stdout']);

        $commandObject
            ->expects(self::once())
            ->method('stdout')
            ->with(...[$expectedString, $expectedColor]);

        $commandObject->{$method}($message);
    }

    public function outputDataProvider(): array
    {
        return [
            'info' => [
                'Info message text',
                'info',
                "Info message text\n",
                Console::FG_BLUE,
            ],
            'success' => [
                'Success message text',
                'success',
                "Success message text\n",
                Console::FG_GREEN,
            ],
            'warning' => [
                'Warning message text',
                'warning',
                "Warning message text\n",
                Console::FG_YELLOW,
            ],
            'error' => [
                'Error message text',
                'error',
                "Error message text\n",
                Console::FG_RED,
            ],
        ];
    }
}
