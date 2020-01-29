<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2020
 */

declare(strict_types=1);

namespace manchenkov\yii\data;

use manchenkov\yii\data\contracts\FormInterface;
use yii\base\Model;

abstract class Form extends Model implements FormInterface
{
    public function obtain(array $data): bool
    {
        if ($this->load($data)) {
            return $this->validate();
        }

        return false;
    }
}