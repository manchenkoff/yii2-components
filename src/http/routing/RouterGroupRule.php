<?php

declare(strict_types=1);

namespace manchenkov\yii\http\routing;

use yii\base\InvalidConfigException;
use yii\web\GroupUrlRule;

final class RouterGroupRule extends GroupUrlRule implements RouterRuleInterface
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
     * Rules config array
     * @var RouterRule[]
     */
    public $rules;

    /**
     * URL address suffix
     * @var string
     */
    public string $suffix;

    public function build(): void
    {
        foreach ($this->rules as $rule) {
            if (!$rule instanceof RouterRule) {
                throw new InvalidConfigException('RouterGroupUrl works with RouterRule objects only');
            }

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
    public function suffix(string $value): RouterGroupRule
    {
        $this->suffix = $value;

        return $this;
    }

    /**
     * Sets the rules configuration array to the group
     *
     * @param RouterRule[] $rules
     *
     * @return static
     */
    public function routes(array $rules): RouterGroupRule
    {
        $this->rules = $rules;

        return $this;
    }
}
