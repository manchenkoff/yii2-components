<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application;

class YiiTestCase extends TestCase
{
    protected function getVendorPath(): string
    {
        $vendor = dirname(__DIR__, 2) . '/vendor';

        if (!is_dir($vendor)) {
            $vendor = dirname(__DIR__, 4);
        }

        return $vendor;
    }

    /**
     * @param array $config
     *
     * @return Application
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function createMockWebApplication(array $config): Application
    {
        return new Application(
            ArrayHelper::merge(
                [
                    'id' => 'test-app',
                    'basePath' => __DIR__,
                    'vendorPath' => $this->getVendorPath(),
                    'aliases' => [
                        '@bower' => '@vendor/bower-asset',
                        '@npm' => '@vendor/npm-asset',
                    ],
                ],
                $config
            )
        );
    }

    protected function destroyMockWebApplication(): void
    {
        Yii::$app->session->close();
        Yii::$app = null;
    }
}
