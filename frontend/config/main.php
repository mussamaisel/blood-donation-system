<?php

declare(strict_types=1);

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
);

return [
    'id' => 'app-frontend',
    'language'       => 'en',
    'sourceLanguage' => 'en',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => \common\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['/auth/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'auth/login',
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'register' => 'auth/register',
                'register-hospital' => 'auth/register-hospital',
                'dashboard' => 'auth/dashboard',
                'donor/dashboard' => 'donor/dashboard',
                'admin/dashboard'      => 'admin/dashboard',
                'admin/donors'         => 'admin/donors',
                'admin/hospitals'      => 'admin/hospitals',
                'admin/blood-requests' => 'admin/blood-requests',
                'admin/blood-stock'    => 'admin/blood-stock',
                'admin/reports'        => 'admin/reports',
                'admin/verify-hospital/<id:\d+>' => 'admin/verify-hospital',
                'admin/delete-donor/<id:\d+>'    => 'admin/delete-donor',
                'admin/delete-hospital/<id:\d+>' => 'admin/delete-hospital',
                'admin/approve-request/<id:\d+>' => 'admin/approve-request',
                'admin/reject-request/<id:\d+>'  => 'admin/reject-request',
                'hospital/dashboard'           => 'hospital/dashboard',
                'hospital/blood-requests'      => 'hospital/blood-requests',
                'hospital/create-request'      => 'hospital/create-request',
                'hospital/blood-stock'         => 'hospital/blood-stock',
                'hospital/add-stock'           => 'hospital/add-stock',
                'hospital/appointments'        => 'hospital/appointments',
                'hospital/approve-appointment/<id:\d+>' => 'hospital/approve-appointment',
                'hospital/reject-appointment/<id:\d+>'  => 'hospital/reject-appointment',
                'donor/appointments'                    => 'donor/appointments',
                'donor/book-appointment'                => 'donor/book-appointment',
                'donor/cancel-appointment/<id:\d+>'     => 'donor/cancel-appointment',
                'language/<lang>' => 'site/language',
                'donor/donations' => 'donor/donations',
                'donor/notifications' => 'donor/notifications',
                'hospital/notifications' => 'hospital/notifications',
                'admin/notifications' => 'admin/notifications',
            ],
        ],
        
    ],
    'params' => $params,
];
