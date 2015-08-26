<?php
use yii\web\Controller;

$params = array_merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/sms.php'),
    require(__DIR__ . '/params.php')
    // require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','gii'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
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
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'l1sZeDzBduDtjC9XTzvjD6n833-lN03m',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => false,
            ],
        ],
        'db' => require(__DIR__ . '/../../common/config/db.php'),
        // 'response' => [
        //     'class' => 'yii\web\Response',
        //     'on beforeSend' => function ($event) {
        //         $response = $event->sender;
        //         if ($response->data !== null) {
        //             //var_dump($response->data);
        //             $error=strstr($response->data,'yii\base\ErrorException',true);
        //             if($error){
        //                 Controller::redirect('/site/error-page.html');
        //             }
                    
        //             //print_r($response->data);exit;
        //             // Yii::$app->request->hostInfo;
                      
        //              //Url::toRoute(['class-detail']);
        //             //Controller::redirect('');
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
    'modules' => [
        'test' => 'app\modules\test',
        'app' => 'app\modules\app\app',
        'weixin' => 'app\modules\weixin\weixin',
        'gii' => 'yii\gii\Module',
                // 'setting' => [
        //     'class' => 'shiyang\setting\Module',
        //     'controllerNamespace' => 'shiyang\setting\controllers'
        // ],
        // 'forum' => [
        //     'class' => 'api\modules\forum\ForumModule',
        //     'aliases' => [
        //         '@forum_icon' => '@web/uploads/forum/icon/', //图标上传路径
        //         '@avatar' => '@web/uploads/user/avatar/',
        //         '@photo' => '@web/uploads/blog/photo/'
        //     ],
        // ],
    ],
];
