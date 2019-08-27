<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\http\routing;

use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

/**
 * Class RouteManager for generate application URL rules
 * @package manchenkov\yii\http\routing
 */
class RouteManager extends UrlManager
{
    /**
     * Base URL request path
     * @var string
     */
    public $baseUrl = '/';

    /**
     * Enable routing with params
     * @var bool
     */
    public $enablePrettyUrl = true;

    /**
     * Disable routing without matches
     * @var bool
     */
    public $enableStrictParsing = true;

    /**
     * Disable 'index.php' visibility
     * @var bool
     */
    public $showScriptName = false;

    /**
     * Directory with routes configuration files
     * @var string
     */
    public $routesDirectory;

    /**
     * Router configuration files loading
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!is_dir($this->routesDirectory)) {
            throw new InvalidConfigException('Routes directory not found');
        }

        $files = FileHelper::findFiles($this->routesDirectory);
        $rules = [];

        // load config files into array
        foreach ($files as $f) {
            $loadedRules = require $f;

            if (is_array($loadedRules)) {
                $rules = array_merge(
                    $rules,
                    $loadedRules
                );
            } else {
                // single rule (like Group)
                $rules[] = $loadedRules;
            }
        }

        // save the rules to the current router array
        $this->addRules(
            // ignore incorrect values
            array_filter($rules, function ($ruleItem) {
                return $ruleItem instanceof UrlRuleInterface;
            })
        );

        // build each rules config
        foreach ($this->rules as $rule) {
            /** @var $rule RouterRule|ResourceRule|RouterGroupRule */
            $rule->build();
        }

        // initialize router component
        return parent::init();
    }
}