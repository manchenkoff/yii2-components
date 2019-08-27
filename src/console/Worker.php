<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\console;

use yii\base\InvalidConfigException;

/**
 * Class Worker for quickly developing daemon components
 *
 * @property string $waitingTime
 */
abstract class Worker extends Command
{
    /**
     * @var string Default controller action name
     */
    public $defaultAction = 'run';

    /**
     * @var bool Uses for controlling handle method
     */
    protected $canWork = true;

    /**
     * @var int Time interval between worker actions
     */
    protected $sleepTime = 30;

    public function actionRun()
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
     * Stop worker by OS signal
     */
    private function stop()
    {
        $this->warning('Stopping worker ...');
        $this->canWork = false;
        $this->onStopped();
    }

    /**
     * OS signal callback function
     */
    private function signalHandler()
    {
        $this->stop();
        $this->success('Worker has stopped');
        exit();
    }

    /**
     * Format 'sleepTime' as time duration
     * @return string
     * @throws InvalidConfigException
     */
    public function getWaitingTime()
    {
        return app()->formatter->asTime($this->sleepTime);
    }

    /**
     * Executes before each handle action
     */
    protected function beforeHandle() { }

    /**
     * Executes after each handle action
     */
    protected function afterHandle() { }

    /**
     * Executes when the worker has stopped
     */
    protected function onStopped() { }

    /**
     * @return mixed
     */
    abstract protected function handle();
}