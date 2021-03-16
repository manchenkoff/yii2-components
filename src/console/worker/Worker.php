<?php

declare(strict_types=1);

namespace manchenkov\yii\console\worker;

use manchenkov\yii\console\command\Command;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Worker for quickly developing daemon components
 *
 * @property string $waitingTime
 */
abstract class Worker extends Command implements WorkerInterface
{
    public $defaultAction = 'run';

    /**
     * @var bool Uses for controlling handle method
     */
    protected bool $canWork = true;

    /**
     * @var int Time interval between worker actions
     */
    protected int $secondsToSleep = 1;

    public function actionRun(): void
    {
        pcntl_signal(SIGQUIT, [$this, 'signalHandler']);
        pcntl_signal(SIGINT, [$this, 'signalHandler']);

        while ($this->isEnabled()) {
            $this->beforeHandle();
            $this->handle();
            $this->afterHandle();

            pcntl_signal_dispatch();

            $this->info("Wait for the next loop ... (Waiting time: {$this->waitingTime})");

            sleep($this->secondsToSleep);
        }
    }

    /**
     * Executes before each handle action
     */
    protected function beforeHandle(): void
    {
    }

    /**
     * @return mixed
     */
    abstract protected function handle(): void;

    /**
     * Executes after each handle action
     */
    protected function afterHandle(): void
    {
    }

    /**
     * Format 'sleepTime' as time duration
     * @return string
     * @throws InvalidConfigException
     */
    public function getWaitingTime(): string
    {
        return Yii::$app->formatter->asTime($this->secondsToSleep);
    }

    /**
     * OS signal callback function
     */
    private function signalHandler(): void
    {
        $this->stop();
        exit();
    }

    /**
     * Stop worker by OS signal
     */
    private function stop(): void
    {
        $this->warning("Stopping worker ...");
        $this->canWork = false;

        $this->onStopped();
    }

    /**
     * Executes when the worker has stopped
     */
    protected function onStopped(): void
    {
    }

    public function isEnabled(): bool
    {
        return $this->canWork;
    }
}
