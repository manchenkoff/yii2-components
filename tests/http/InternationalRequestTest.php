<?php

declare(strict_types=1);

namespace tests\http;

use manchenkov\yii\http\InternationalRequest;
use tests\YiiTestCase;

class InternationalRequestTest extends YiiTestCase
{
    public function testRequestWithoutLanguages(): void
    {
        $app = $this->createMockWebApplication(
            [
                'components' => [
                    'request' => [
                        'class' => InternationalRequest::class,
                        'baseUrl' => '',
                        'cookieValidationKey' => 'secret-key',
                    ],
                ],
            ]
        );

        $_SERVER['REQUEST_URI'] = 'https://developer.dev/home';

        self::assertEquals('', $app->request->getBaseUrl());
        self::assertEquals('/home', $app->request->getUrl());

        $this->destroyMockWebApplication();
    }

    /**
     * @dataProvider languageDataProvider
     *
     * @param string $url
     * @param string $expectedBaseUrl
     * @param string $expectedRoute
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function testRequestWithLanguages(string $url, string $expectedBaseUrl, string $expectedRoute): void
    {
        $app = $this->createMockWebApplication(
            [
                'components' => [
                    'request' => [
                        'class' => InternationalRequest::class,
                        'baseUrl' => '',
                        'cookieValidationKey' => 'secret-key',
                        'languages' => ['ru', 'en'],
                    ],
                ],
            ]
        );

        $_SERVER['REQUEST_URI'] = $url;

        self::assertEquals($expectedRoute, $app->request->getUrl());
        self::assertEquals($expectedBaseUrl, $app->request->getBaseUrl());

        $this->destroyMockWebApplication();
    }

    public function languageDataProvider(): array
    {
        return [
            'no language in URL' => [
                'https://developer.dev/home',
                '',
                '/home',
            ],

            'supported language in URL' => [
                'https://developer.dev/en/home',
                '/en',
                '/en/home',
            ],

            'unsupported language in URL' => [
                'https://developer.dev/jp/home',
                '',
                '/jp/home',
            ],
        ];
    }
}
