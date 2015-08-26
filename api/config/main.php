<?php
//use Yii;
use yii\web\Controller;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/sms.php'),
    require(__DIR__ . '/../../common/config/alipay.config.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$db = require(__DIR__ . '/../../common/config/db.php');
$modules = require(__DIR__ . '/apiModules.php');

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' =>$modules,
    'components' => [
        'db' => $db,
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        // 'response' => [
        //     'class' => 'yii\web\Response',
        //     'on beforeSend' => function ($event) {
        //         $response = $event->sender;
        //         if ($response->data !== null) {
        //             // Yii::$app->request->hostInfo;
        //             // Controller::redirect(Yii::$app->request->hostInfo);
        //            // Controller::render('/api/views/site/test',['message'=>'hahah']);
        //             // $response->data = [
        //             //     'success111' => $response->isSuccessful,
        //             //     'data' => $response->data,
        //             // ];
        //             // $response->statusCode = 200;
        //         }
        //     },
        // ],
    ],
    'params' => $params,
];
