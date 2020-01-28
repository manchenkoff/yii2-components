<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\http\routing;

use yii\base\InvalidConfigException;
use yii\web\GroupUrlRule;

class RouterGroupRule extends GroupUrlRule
{
    /**
     * URL pattern route prefix
     * @var string
     */
    public $prefix;

    /**
     * Controller or module name prefix
     * @var string
     */
    public $routePrefix;

    /**
     * URL address suffix
     * @var string
     */
    public $suffix;

    /**
     * Rules config array
     * @var array
     */
    public $rules = [];

    /**
     * Builds group rules
     * @throws InvalidConfigException
     */
    public function build(): void
    {
        foreach ($this->rules as $rule) {
            /** @var $rule RouterRule */
            $rule->pattern = "{$this->prefix}/{$rule->pattern}";
            $rule->prefix = $this->routePrefix;

            $rule->build();
        }
    }

    /**
     * Appends a suffix to the whole group routes
     *
     * @param string $value
     *
     * @return static
     */
    public function suffix(string $value)
    {
        $this->suffix = $value;

        return $this;
    }

    /**
     * Sets the rules configuration array to the group
     *
     * @param array $rules
     *
     * @return static
     */
    public function routes(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }
}