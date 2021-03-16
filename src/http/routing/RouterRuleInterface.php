<?php

declare(strict_types=1);

namespace manchenkov\yii\http\routing;

use yii\base\InvalidConfigException;

interface RouterRuleInterface
{
    /**
     * @throws InvalidConfigException
     */
    public function build(): void;
}
