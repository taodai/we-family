<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

$db = require(__DIR__ . '/../../common/config/db.php');
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log','debug','gii'],
    'modules' => [
        'system' => 'app\modules\system\system',
        'content' => 'app\modules\content\content',
        'product' => 'app\modules\product\product',
        'weixin' => 'app\modules\weixin\weixin',
        'ucenter' => [
            'class' => 'app\modules\ucenter\ucenter',
        ],
        'api' => [
            'class' => 'backend\modules\apiManage\apiManage',
        ],
        'product' => [
            'class' => 'backend\modules\product\product',
        ],
        'group' => [
            'class' => 'app\modules\group\group',
        ],
        // 'debug' => 'yii\debug\Module',
        'gii' => 'yii\gii\Module',
        'debug' => [
            'class' => 'yii\debug\Module',
            //是否支持远程调试
            'allowedIPs' => ['127.0.0.1', '220.184.99.231'],
        ],
    ],
    'components' => [
        'db' => $db,
        'request' => [
            'enableCookieValidation' => false,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        ],
        'user' => [
            'identityClass' => 'backend\models\Manager',
            // 'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
