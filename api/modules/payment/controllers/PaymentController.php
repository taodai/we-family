<?php

namespace api\modules\payment\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class PaymentController extends AbstractController
{

    public $modelClass = 'common\models\UserPayment';
    public $session = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function init()
    {
        $this->session = Yii::$app->session;
    }
    /**
    * 自定义方法开启 
    */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);

        return $actions;
    }

    // 添加支付记录
    public function actionAddPayment()
    {
        if(isset($this->params['uid']) && isset($this->params['money'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->addPayment($this->params);
            if($response){
                $this->_formatResponse(1,'添加成功');
            }else{
                $this->_formatResponse(2,'添加失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }
    //查询用户所有支付记录
    public function actionGetPaymentList()
    {
        if(isset($this->params['uid'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAll($this->params['uid']);
            if($response){
                $this->_formatResponse(1,'获取用户支付信息列表成功',$response);
            }else{
                $this->_formatResponse(2,'获取用户支付信息列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }
}
