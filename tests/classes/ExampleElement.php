<?php

declare(strict_types=1);

namespace tests\classes;

/**
 * Example DTO class just for test cases
 */
final class ExampleElement
{
    public string $value;
    public int $number;

    public function __construct(string $value, int $number = 0)
    {
        $this->value = $value;
        $this->number = $number;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
