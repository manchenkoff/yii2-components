<?php

declare(strict_types=1);

namespace manchenkov\yii\console\worker;

interface WorkerInterface
{
    /**
     * Main worker controller loop action
     */
    public function actionRun(): void;
}
