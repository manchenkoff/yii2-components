<?php

declare(strict_types=1);

namespace manchenkov\yii\console\command;

use yii\console\Controller as ConsoleController;
use yii\helpers\Console;

abstract class Command extends ConsoleController implements CommandInterface
{
    private const COLOR_SUCCESS = Console::FG_GREEN;
    private const COLOR_INFO = Console::FG_BLUE;
    private const COLOR_ERROR = Console::FG_RED;
    private const COLOR_WARNING = Console::FG_YELLOW;

    /**
     * @var bool Use terminal colors if supports
     */
    public $color = true;

    public function success(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_SUCCESS);
    }

    public function info(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_INFO);
    }

    public function error(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_ERROR);
    }

    public function warning(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_WARNING);
    }

    private function coloredMessage(string $message, int $color): void
    {
        $this->stdout($message . PHP_EOL, $color);
    }
}
