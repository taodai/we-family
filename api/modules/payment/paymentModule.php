<?php

namespace api\modules\payment;
use Yii;
use yii\web\Response;

class paymentModule extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\payment\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    // public function beforeAction($action)
    // { 

    //     $method = Yii::$app->request->getMethod();

    //     $params = ($method == 'GET') ? Yii::$app->request->get() : Yii::$app->request->post();

    //     if(isset($params['appId']) && isset($params['appKey'])){
    //         return parent::beforeAction($action);
    //     }else{
    //         Yii::$app->response->format = Response::FORMAT_JSON;
    //         Yii::$app->response->data = array(
    //             'code' => 0,
    //             'msg' => '非法应用'
    //         );
    //         return false;
    //     }
    // }
}
