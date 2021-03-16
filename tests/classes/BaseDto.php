<?php

declare(strict_types=1);

namespace tests\classes;

abstract class BaseDto
{
    /**
     * @return int|bool
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    public function delete()
    {
        return true;
    }

    public function save(): bool
    {
        return true;
    }
}
