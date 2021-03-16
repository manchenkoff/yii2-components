<?php

declare(strict_types=1);

namespace manchenkov\yii\http\routing;

use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\UrlManager;

/**
 * Class RouteManager for generate application URL rules
 * @package manchenkov\yii\http\routing
 */
final class RouteManager extends UrlManager
{
    public $baseUrl = '/';
    public $enablePrettyUrl = true;
    public $enableStrictParsing = true;
    public $showScriptName = false;

    /**
     * Directory with routes configuration files
     * @var string
     */
    public string $routesDirectory;

    /**
     * Router configuration files loading
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (!is_dir($this->routesDirectory)) {
            throw new InvalidConfigException('Routes directory not found');
        }

        $routes = $this->collectRoutesFromConfiguration();

        $validatedRouteCollection = array_filter(
            $routes,
            static function ($ruleItem): bool {
                return $ruleItem instanceof RouterRuleInterface;
            }
        );

        $this->addRules($validatedRouteCollection);

        foreach ($this->rules as $rule) {
            /** @var $rule RouterRuleInterface */
            $rule->build();
        }

        parent::init();
    }

    /**
     * @return RouterRuleInterface[]
     * @noinspection PhpIncludeInspection
     */
    private function collectRoutesFromConfiguration(): array
    {
        $files = FileHelper::findFiles($this->routesDirectory);
        $rules = [];

        foreach ($files as $f) {
            $loadedRules = require $f;

            if (is_array($loadedRules)) {
                $rules = array_merge($rules, $loadedRules);
                continue;
            }

            $rules[] = $loadedRules;
        }

        return $rules;
    }
}
