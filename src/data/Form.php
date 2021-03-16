<?php

declare(strict_types=1);

namespace manchenkov\yii\data;

use yii\base\Model;

abstract class Form extends Model
{
    public function check($data, $formName = null): bool
    {
        $loaded = $this->load($data, $formName);

        if ($loaded) {
            return $this->validate();
        }

        return false;
    }
}
