<?php

declare(strict_types=1);

namespace manchenkov\yii\http\routing;

use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use yii\web\CompositeUrlRule;

/**
 * Class ResourceRule automatically creates the URL rules for a resource controller
 *
 * Example:
 *    'GET posts' => 'post/index',
 *    'POST posts' => 'post/store',
 *    'GET posts/<token>' => 'post/view',
 *    'GET posts/<token>/edit' => 'post/edit',
 *    'POST posts/<token>' => 'post/update',
 *    'DELETE posts/<token>' => 'post/delete',
 *
 * @package manchenkov\yii\http\routing
 */
final class ResourceRule extends CompositeUrlRule implements RouterRuleInterface
{
    /**
     * Resource controller name/id
     * @var string
     */
    public string $controller;

    /**
     * Enables an inflection on a controller name in the URL
     * @var bool
     */
    public bool $pluralize;

    /**
     * Controller key to search models
     * Default value is resource controller name: `$this->controller`
     * @var string|null
     */
    public ?string $token = null;

    /**
     * URL prefix
     * @var string|null
     */
    public ?string $prefix = null;

    /**
     * Additional rules for resource controller (without controller name)
     * @var RouterRule[]
     */
    public array $extraRules = [];

    /**
     * Overrides parent action
     */
    public function init(): void
    {
        $this->rules = [];
    }

    public function build(): void
    {
        $this->rules = array_merge(
            $this->resourceRules(),
            $this->extraRules
        );

        $this->createRules();
    }

    /**
     * Defaults resource controller routes
     * @return RouterRule[]
     */
    protected function resourceRules(): array
    {
        if ($this->token === null) {
            $this->token = $this->controller;
        }

        return [
            // GET controller => controller/index
            Route::get("", "index"),
            // POST controller => controller/store
            Route::post("", "store"),

            // GET controller/<id> => controller/view
            Route::get("<{$this->token}>", "view"),
            // GET controller/<id>/edit => controller/edit
            Route::get("<{$this->token}>/edit", "edit"),
            // POST controller/<id> => controller/update
            Route::post("<{$this->token}>", "update"),
            // DELETE controller/<id> => controller/delete
            Route::delete("<{$this->token}>", "delete"),
        ];
    }

    /**
     * Creates the URL rules for a resource controller with normalization
     *
     * @throws InvalidConfigException
     */
    protected function createRules(): void
    {
        // build full controller URL prefix
        $normalizedPrefix = $this->pluralize
            ? Inflector::pluralize($this->controller)
            : $this->controller;

        // append custom prefix if exists
        if ($this->prefix !== null) {
            $normalizedPrefix = "{$this->prefix}/{$normalizedPrefix}";
        }

        // normalize each rule
        foreach ($this->rules as $rule) {
            if (!$rule instanceof RouterRule) {
                throw new InvalidConfigException('ResourceRule works with RouterRule objects only');
            }

            $rule->pattern = rtrim("{$normalizedPrefix}/{$rule->pattern}", '/');
            $rule->route = "{$this->controller}/{$rule->route}";

            $rule->build();
        }
    }

    /**
     * Changes resources primary key name
     *
     * @param string $name
     *
     * @return static
     */
    public function token(string $name): ResourceRule
    {
        $this->token = $name;

        return $this;
    }

    /**
     * Appends custom prefix to the URL route
     *
     * @param string $urlPrefix
     *
     * @return static
     */
    public function prefix(string $urlPrefix): ResourceRule
    {
        $this->prefix = $urlPrefix;

        return $this;
    }

    /**
     * Appends custom URL rules to the resource controller
     *
     * @param RouterRule[] $rules
     *
     * @return static
     */
    public function extra(array $rules): ResourceRule
    {
        $this->extraRules = $rules;

        return $this;
    }
}
