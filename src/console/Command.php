<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\console;

use Exception;
use manchenkov\yii\console\contracts\CommandInterface;
use ReflectionException;
use ReflectionMethod;
use Yii;
use yii\base\Action;
use yii\base\InlineAction;
use yii\console\Controller as BaseController;
use yii\helpers\Console;

/**
 * Console controller base class with a few helper methods and DI in actions
 */
abstract class Command extends BaseController implements CommandInterface
{
    /**
     * @var bool Use terminal colors if supports
     */
    public $color = true;

    /**
     * Color constants
     */
    const COLOR_SUCCESS = Console::FG_GREEN;
    const COLOR_INFO = Console::FG_BLUE;
    const COLOR_ERROR = Console::FG_RED;
    const COLOR_WARNING = Console::FG_YELLOW;

    /**
     * Print a message with text color
     *
     * @param string $message
     * @param int $color
     */
    private function coloredMessage(string $message, int $color): void
    {
        $this->stdout($message . PHP_EOL, $color);
    }

    public function success(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_SUCCESS);
    }

    public function info(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_INFO);
    }

    public function error(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_ERROR);
    }

    public function warning(string $message): void
    {
        $this->coloredMessage($message, self::COLOR_WARNING);
    }

    /**
     * @param Action $action
     * @param array $params
     *
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    public function bindActionParams($action, $params): array
    {
        // make callable items for resolving
        if ($action instanceof InlineAction) {
            $callable = [$this, $action->actionMethod];
        } else {
            $callable = [$action, 'run'];
        }

        // get an instance of a requested controller method
        $method = new ReflectionMethod(...$callable);

        // resolving dependencies with Yii container
        $injectedParams = Yii::$container->resolveCallableDependencies($callable, $params);

        // set necessary objects to the named parameters
        foreach ($method->getParameters() as $idx => $parameter) {
            $params[$idx] = $injectedParams[$idx];
        }

        $args = array_values($params);

        $missing = [];

        foreach ($method->getParameters() as $i => $param) {
            if ($param->isArray() && isset($args[$i])) {
                $args[$i] = $args[$i] === '' ? [] : preg_split('/\s*,\s*/', $args[$i]);
            }
            if (!isset($args[$i])) {
                if ($param->isDefaultValueAvailable()) {
                    $args[$i] = $param->getDefaultValue();
                } else {
                    $missing[] = $param->getName();
                }
            }
        }

        if (!empty($missing)) {
            throw new Exception(
                t('yii', 'Missing required arguments: {params}', [
                    'params' => implode(', ', $missing),
                ])
            );
        }

        return $args;
    }
}