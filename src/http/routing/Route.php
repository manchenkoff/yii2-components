<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\http\routing;

class Route
{
    /**
     * Builds a group of routes objects
     *
     * @param string $urlPrefix
     * @param string|null $routePrefix
     *
     * @return RouterGroupRule
     */
    public static function group(string $urlPrefix, string $routePrefix = null): RouterGroupRule
    {
        return new RouterGroupRule(
            [
                'prefix' => $urlPrefix,
                'routePrefix' => $routePrefix ?? $urlPrefix,
            ]
        );
    }

    /**
     * Builds a predefined resource template route object
     *
     * @param string $controller
     * @param bool $pluralize
     *
     * @return ResourceRule
     */
    public static function resource(string $controller, bool $pluralize = true): ResourceRule
    {
        return new ResourceRule(
            [
                'controller' => $controller,
                'pluralize' => $pluralize,
            ]
        );
    }

    /**
     * Returns a GET rule object
     *
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function get(string $route, string $action): RouterRule
    {
        return self::buildRule('get', $route, $action);
    }

    /**
     * Builds a route object
     *
     * @param string|null $method
     * @param string $url
     * @param string $action
     *
     * @return RouterRule
     */
    private static function buildRule(?string $method, string $url, string $action): RouterRule
    {
        $config = [
            'pattern' => $url,
            'route' => $action,
        ];

        if ($method !== null) {
            $config['verb'] = [$method];
        }

        return new RouterRule($config);
    }

    /**
     * Returns a POST rule object
     *
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function post(string $route, string $action): RouterRule
    {
        return self::buildRule('post', $route, $action);
    }

    /**
     * Returns a PUT rule object
     *
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function put(string $route, string $action): RouterRule
    {
        return self::buildRule('put', $route, $action);
    }

    /**
     * Returns a PATCH rule object
     *
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function patch(string $route, string $action): RouterRule
    {
        return self::buildRule('patch', $route, $action);
    }

    /**
     * Returns a DELETE rule object
     *
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function delete(string $route, string $action): RouterRule
    {
        return self::buildRule('delete', $route, $action);
    }

    /**
     * Returns a rule object for any of REQUEST method types
     *
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function any(string $route, string $action): RouterRule
    {
        return self::buildRule(null, $route, $action);
    }

    /**
     * Returns a rule object to handle multiple REQUEST method types
     *
     * @param array $methods
     * @param string $route
     * @param string $action
     *
     * @return RouterRule
     */
    public static function matches(array $methods, string $route, string $action): RouterRule
    {
        $methods = implode(',', $methods);

        return self::buildRule($methods, $route, $action);
    }
}