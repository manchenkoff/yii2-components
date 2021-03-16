<?php

declare(strict_types=1);

namespace manchenkov\yii\http;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\Cookie;
use yii\web\Request as HttpRequest;

/**
 * Class InternationalRequest for multi-language websites support by URL
 * For example: "domain.com/en" = english, "domain.com/ru" = russian, "domain.com" = default/cookie
 *
 * @example Set application components config like following:
 * ```
 * 'request' => [
 *      'class' => InternationalRequest::class,
 *      'languages' => ['ru', 'en'],
 *      ...
 * ],
 * ```
 */
final class InternationalRequest extends HttpRequest
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
        $url = parent::resolveRequestUri();

        $language = $this->obtainApplicationLanguage($url);

        if ($language !== null) {
            Yii::$app->language = $language;
            $this->baseUrl .= "/{$language}";
        }

        return $url;
    }

    private function obtainApplicationLanguage(string $url): ?string
    {
        $languageFromRequest = $this->extractLanguageFromUrl($url);
        $languageFromCookie = $this->extractLanguageFromCookies();

        if ($languageFromRequest === null && $languageFromCookie === null) {
            return null;
        }

        if ($languageFromRequest !== null && $languageFromRequest !== $languageFromCookie) {
            $cookie = new Cookie(
                [
                    'name' => $this->languageCookieName,
                    'value' => $languageFromRequest,
                    'secure' => true,
                    'expire' => strtotime($this->languageCookieLifetime),
                ]
            );

            Yii::$app
                ->response
                ->cookies
                ->add($cookie);
        }

        return $languageFromRequest ?? $languageFromCookie;
    }

    private function extractLanguageFromUrl(string $url): ?string
    {
        $parameters = array_filter(explode('/', $url));

        if (count($parameters) === 0) {
            return null;
        }

        $prefix = current($parameters);

        if (in_array($prefix, $this->languages, true)) {
            return $prefix;
        }

        return null;
    }

    private function extractLanguageFromCookies(): ?string
    {
        $language = $this->cookies->get($this->languageCookieName);

        return $language ? $language->value : null;
    }
}
