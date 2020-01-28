<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\database\traits;

use yii\base\Model;

/**
 * Trait SafeModel allows loading Models without 'formName' property
 *
 * @mixin Model
 */
trait SafeModel
{
    /**
     * @inheritDoc
     */
    public function formName(): string
    {
        return '';
    }
}