<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\http;

use yii\base\InvalidConfigException;
use yii\web\Cookie;
use yii\web\Request as BaseRequest;

/**
 * Class Request for multi-language websites support by URL
 * For example: "domain.com/en" = english, "domain.com/ru" = russian, "domain.com" = default/cookie
 *
 * @example Set application components config like following:
 * ```
 * 'request' => [
 *      'class' => Request::class,
 *      'languages' => ['ru', 'en'],
 *      ...
 * ],
 * ```
 *
 * @property string $languageFromUrl
 */
class Request extends BaseRequest
{
    /**
     * Language cookie name value
     * @var string
     */
    public string $languageCookieName = '_language';

    /**
     * Language cookie lifetime string
     * @var string
     */
    public string $languageCookieLifetime = '+1 month';

    /**
     * Supported languages list
     * @var array
     */
    public array $languages = [];

    /**
     * Requested URL processing
     *
     * @return string
     * @throws InvalidConfigException
     */
    protected function resolveRequestUri(): string
    {
        $result = parent::resolveRequestUri();

        return $this->setLanguageFromUrl($result);
    }

    /**
     * Gets and sets a language from URL string
     *
     * @param string $url
     *
     * @return string
     */
    private function setLanguageFromUrl(string $url): string
    {
        $cookieLanguage = $this->cookies[$this->languageCookieName];

        // split URL into separate parts
        $urlParts = array_filter(explode('/', $url));

        // try to find language prefix
        if (count($urlParts) > 0) {
            $languagePart = current($urlParts);

            // if founded language is supported
            if (in_array($languagePart, $this->languages)) {
                // set browser cookie
                if ($cookieLanguage != $languagePart) {
                    app()->response->cookies->add(
                        new Cookie(
                            [
                                'name' => $this->languageCookieName,
                                'value' => $languagePart,
                                'secure' => true,
                                'expire' => strtotime($this->languageCookieLifetime),
                            ]
                        )
                    );
                }

                // change application current language
                app()->language = $languagePart;

                // change base application URL
                $this->baseUrl = "/{$languagePart}";
            }
        } else {
            // set language from cookie
            if ($cookieLanguage && $cookieLanguage != app()->language) {
                app()->language = $cookieLanguage;
            }
        }

        return $url;
    }
}