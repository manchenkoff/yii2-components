<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\http;

use ReflectionException;
use ReflectionMethod;
use Yii;
use yii\base\InlineAction;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Trait ActionDependencies to work with action dependency injection arguments
 * @mixin \yii\web\Controller
 */
trait ActionDependencies
{
    /**
     * {@inheritdoc}
     *
     * @throws ReflectionException
     * @throws NotFoundHttpException
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
        try {
            $injectedParams = Yii::$container->resolveCallableDependencies($callable, $params);

            // set necessary objects to the named parameters
            foreach ($method->getParameters() as $idx => $parameter) {
                // find ActiveRecord instance by PK
                if ($injectedParams[$idx] instanceof ActiveRecordInterface) {
                    $activeRecord = $injectedParams[$idx];

                    $injectedParams[$idx] = $activeRecord::findOne($params[$parameter->name]);

                    if (!$injectedParams[$idx]) {
                        throw new NotFoundHttpException();
                    }
                }

                $params[$parameter->name] = $injectedParams[$idx];
            }
        } catch (InvalidConfigException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $args = [];
        $missing = [];
        $actionParams = [];

        foreach ($method->getParameters() as $param) {
            $name = $param->getName();

            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } else {
                    if (!is_array($params[$name])) {
                        $args[] = $actionParams[$name] = $params[$name];
                    } else {
                        throw new BadRequestHttpException(
                            t(
                                'yii',
                                'Invalid data received for parameter "{param}".',
                                [
                                    'param' => $name,
                                ]
                            )
                        );
                    }
                }

                unset($params[$name]);
            } else {
                if ($param->isDefaultValueAvailable()) {
                    $args[] = $actionParams[$name] = $param->getDefaultValue();
                } else {
                    $missing[] = $name;
                }
            }
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException(
                t(
                    'yii',
                    'Missing required parameters: {params}',
                    [
                        'params' => implode(', ', $missing),
                    ]
                )
            );
        }

        $this->actionParams = $actionParams;

        return $args;
    }
}