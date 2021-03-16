<?php

declare(strict_types=1);

namespace manchenkov\yii\http\routing;

use Yii;
use yii\web\UrlRule;

final class RouterRule extends UrlRule implements RouterRuleInterface
{
    /**
     * URL route alias to quick access in the views/controllers
     * @var string
     */
    public string $alias;

    /**
     * Group prefix for alias action route
     * @var string
     */
    public string $prefix;

    /**
     * Overrides parent action
     */
    public function init(): void
    {
        /*__*/
    }

    public function build(): void
    {
        if (!empty($this->prefix)) {
            $this->route = "{$this->prefix}/{$this->route}";
        }

        if (!empty($this->alias)) {
            Yii::setAlias("@{$this->alias}", $this->route);
        }

        parent::init();
    }

    /**
     * Sets route alias like 'home' = Url::to(['@home'])
     *
     * @param string $value
     *
     * @return static
     */
    public function name(string $value): RouterRule
    {
        $this->alias = $value;

        return $this;
    }

    /**
     * Appends a suffix to the current route
     *
     * @param string $value
     *
     * @return static
     */
    public function suffix(string $value): RouterRule
    {
        $this->suffix = $value;

        return $this;
    }

    /**
     * Sets a default GET parameters
     *
     * @param array $context
     *
     * @return static
     */
    public function defaults(array $context = []): RouterRule
    {
        $this->defaults = $context;

        return $this;
    }
}
