<?php

declare(strict_types=1);

namespace tests\classes;

use manchenkov\yii\database\traits\SoftDelete;

final class SoftDeleteDto extends BaseDto
{
    public ?int $deleted_at = null;

    use SoftDelete;
}
