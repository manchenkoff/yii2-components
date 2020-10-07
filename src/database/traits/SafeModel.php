<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\database\traits;

use yii\base\Model;

/**
 * Trait SafeModel allows loading Models without 'formName' property
 *
 * @mixin Model
 */
trait SafeModel
{
    public function formName(): string
    {
        return '';
    }
}