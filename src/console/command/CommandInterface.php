<?php

declare(strict_types=1);

namespace manchenkov\yii\console\command;

interface CommandInterface
{
    /**
     * Shows message with Console::FG_GREEN color
     *
     * @param string $message
     */
    public function success(string $message): void;

    /**
     * Shows message with Console::FG_BLUE color
     *
     * @param string $message
     */
    public function info(string $message): void;

    /**
     * Shows message with Console::FG_RED color
     *
     * @param string $message
     */
    public function error(string $message): void;

    /**
     * Shows message with Console::FG_YELLOW color
     *
     * @param string $message
     */
    public function warning(string $message): void;
}
