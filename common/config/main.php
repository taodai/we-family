<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'scriptUrl' => '',
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'memberApi/member'],
            ],
        ],
    ],
];
