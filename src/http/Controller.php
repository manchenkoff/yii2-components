<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\http;

use yii\web\Controller as BaseController;
use yii\web\Response;

/**
 * Base Http Controller class with Action DI support and SEO helper component
 */
abstract class Controller extends BaseController
{
    /**
     * Dependency injection in action method
     */
    use ActionDependencies;

    /**
     * Redirect to another route with session flash data
     *
     * @param $url array|string
     * @param $key string
     * @param mixed $value
     *
     * @return Response
     */
    public function redirectFlash($url, string $key, $value)
    {
        session()->setFlash($key, $value);

        return $this->redirect($url);
    }

    /**
     * Redirects to another URL without losing current GET parameters
     *
     * @param $url
     *
     * @return Response
     */
    public function redirectQuery($url)
    {
        $currentParams = request()->get();

        if (is_array($url)) {
            $redirectUrl = array_shift($url);
            $redirectParams = array_merge($url, $currentParams);
        } else {
            $redirectUrl = $url;
            $redirectParams = $currentParams;
        }

        array_unshift($redirectParams, $redirectUrl);

        return $this->redirect($redirectParams);
    }
}