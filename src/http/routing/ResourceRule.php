<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

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
class ResourceRule extends CompositeUrlRule
{
    /**
     * Resource controller name/id
     * @var string
     */
    public $controller;

    /**
     * Enables an inflection on a controller name in the URL
     * @var bool
     */
    public $pluralize;

    /**
     * Controller key to search models
     * Default value is resource controller name: `$this->controller`
     * @var string
     */
    protected $token;

    /**
     * URL prefix
     * @var string
     */
    protected $prefix;

    /**
     * Additional rules for resource controller (without controller name)
     * @var RouterRule[]
     */
    protected $extraRules = [];

    /**
     * Overrides parent action
     */
    public function init()
    {
        $this->rules = [];
    }

    /**
     * Defaults resource controller routes
     * @return array
     */
    protected function resourceRules()
    {
        if (is_null($this->token)) {
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
     * Builds all of the resource controller routes
     * @throws InvalidConfigException
     */
    public function build()
    {
        $this->rules = array_merge(
            $this->resourceRules(),
            $this->extraRules
        );

        $this->createRules();
    }

    /**
     * Creates the URL rules for a resource controller with normalization
     *
     * @throws InvalidConfigException
     */
    protected function createRules()
    {
        // build full controller URL prefix
        $normalizedPrefix = $this->pluralize
            ? Inflector::pluralize($this->controller)
            : $this->controller;

        // append custom prefix if exists
        if ($this->prefix) {
            $normalizedPrefix = "{$this->prefix}/{$normalizedPrefix}";
        }

        // normalize each rule
        foreach ($this->rules as $rule) {
            // check rules class
            if (!$rule instanceof RouterRule) {
                throw new InvalidConfigException('All rules must be an instance of RouterRule class');
            }

            // set URL prefix
            $rule->pattern = rtrim("{$normalizedPrefix}/{$rule->pattern}", '/');

            // append controller path to the actions
            $rule->route = "{$this->controller}/{$rule->route}";

            $rule->build();
        }
    }

    /**
     * Changes resources primary key name
     *
     * @param string $name
     *
     * @return $this
     */
    public function token(string $name)
    {
        $this->token = $name;

        return $this;
    }

    /**
     * Appends custom prefix to the URL route
     *
     * @param string $urlPrefix
     *
     * @return $this
     */
    public function prefix(string $urlPrefix)
    {
        $this->prefix = $urlPrefix;

        return $this;
    }

    /**
     * Appends custom URL rules to the resource controller
     *
     * @param RouterRule[] $rules
     *
     * @return $this
     */
    public function extra(array $rules)
    {
        $this->extraRules = $rules;

        return $this;
    }
}