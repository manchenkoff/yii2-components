<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2020
 */

declare(strict_types=1);

namespace manchenkov\yii\console\contracts;

interface WorkerInterface
{
    /**
     * Main worker controller loop action
     */
    public function actionRun(): void;
}