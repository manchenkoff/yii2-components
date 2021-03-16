<?php

declare(strict_types=1);

const YII_DEBUG = true;
const YII_ENV = 'test';

$vendor = __DIR__ . '/../vendor/';

$composerLoader = $vendor . 'autoload.php';

if (is_file($composerLoader)) {
    require_once $composerLoader;
}

require_once $vendor . 'yiisoft/yii2/Yii.php';
