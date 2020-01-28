<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\http\rest;

use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\ForbiddenHttpException;

/**
 * Middleware contains useful predefined settings to using in API controllers for auth and access control
 */
trait Middleware
{
    /**
     * Basic behaviors: TokenAuth (Query, Bearer), AccessControl
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => $this->publicActions(),
            'authMethods' => [
                [
                    'class' => QueryParamAuth::class,
                    'tokenParam' => 'token',
                ],
                [
                    'class' => HttpBearerAuth::class,
                ],
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => $this->accessRules(),
            'denyCallback' => function () {
                throw new ForbiddenHttpException("You don't have permission to access this page");
            },
        ];

        return $behaviors;
    }

    /**
     * AccessControl rules
     * @return array
     *
     * @example
     * ```php
     * return [
     *     [
     *         'allow' => true|false,
     *         'roles' => ['?'],
     *         'action' => ['index', 'action']
     *     ],
     * ];
     * ```
     */
    abstract protected function accessRules(): array;

    /**
     * Array of actions without authentication
     * @return array
     */
    protected function publicActions(): array
    {
        return [];
    }
}