<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\console;

use manchenkov\yii\console\contracts\WorkerInterface;
use yii\base\InvalidConfigException;

/**
 * Class Worker for quickly developing daemon components
 *
 * @property string $waitingTime
 */
abstract class Worker extends Command implements WorkerInterface
{
    /**
     * @var string Default controller action name
     */
    public $defaultAction = 'run';

    /**
     * @var bool Uses for controlling handle method
     */
    protected bool $canWork = true;

    /**
     * @var int Time interval between worker actions
     */
    protected int $sleepTime = 30;

    public function actionRun(): void
    {
        // handle OS signals
        pcntl_signal(SIGQUIT, [$this, 'signalHandler']);
        pcntl_signal(SIGINT, [$this, 'signalHandler']);

        // main infinite loop
        while ($this->canWork) {
            $this->beforeHandle();

            $this->info("Start processing ...");
            $this->handle();
            $this->info("End processing ...");

            $this->afterHandle();

            pcntl_signal_dispatch();

            $this->info("Wait for the next loop ... (Waiting time: {$this->waitingTime})");
            sleep($this->sleepTime);
        }
    }

    /**
     * Executes before each handle action
     */
    protected function beforeHandle(): void { }

    /**
     * @return mixed
     */
    abstract protected function handle(): void;

    /**
     * Executes after each handle action
     */
    protected function afterHandle(): void { }

    /**
     * Format 'sleepTime' as time duration
     * @return string
     * @throws InvalidConfigException
     */
    public function getWaitingTime(): string
    {
        return app()->formatter->asTime($this->sleepTime);
    }

    /**
     * OS signal callback function
     */
    private function signalHandler(): void
    {
        $this->stop();
        $this->success('Worker has stopped');
        exit();
    }

    /**
     * Stop worker by OS signal
     */
    private function stop(): void
    {
        $this->warning('Stopping worker ...');
        $this->canWork = false;
        $this->onStopped();
    }

    /**
     * Executes when the worker has stopped
     */
    protected function onStopped(): void { }
}