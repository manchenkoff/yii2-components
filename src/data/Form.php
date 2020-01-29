<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2020
 */

declare(strict_types=1);

namespace manchenkov\yii\data;

use yii\base\Model;

abstract class Form extends Model
{
    public function load($data, $formName = null): bool
    {
        $loaded = parent::load($data, $formName);

        if ($loaded) {
            return $this->validate();
        }

        return false;
    }
}