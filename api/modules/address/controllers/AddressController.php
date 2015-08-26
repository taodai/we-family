<?php

namespace api\modules\address\controllers;

use common\models\AppAddress;
use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
// use common\models\AppAddress;

class AddressController extends AbstractController
{

    public $modelClass = 'common\models\AppAddress';
    public $session = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    // public function behaviors()
    // {
    //     $behaviors = parent::behaviors();
    //     $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
    //     return $behaviors;
    // }


    public function init()
    {
        $this->session = Yii::$app->session;
    }

    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        return true;
    }

    //获取用户收货地址列表
    public function actionGetAddress()
    {
        $post = Yii::$app->request->post();
        if(isset($post['userid'])){
            $modelClass = new $this->modelClass;
            $model = new AppAddress();
            $response = $model->find()->joinWith('province')->joinWith('city')->joinWith('area')->where(['userid'=>$post['userid']])->asArray()->all();
            if($response){
                // $this->_code = 1;
                // $this->_msg = '地址列表获取成功';
                $this->_formatResponse(1,'地址列表获取成功',$response);
            }else{
                $this->_formatResponse(2,'获取列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    public function actionAddressAdd()
    {
        $post = Yii::$app->request->post();
        if(isset($post['userid'])){
            $modelClass = new $this->modelClass;
            $count = $modelClass::find()->where(['userid'=>$post['userid']])->count();
            if($count < 10){
                $modelClass->loadValue($post);
                if($modelClass->save()){
                    $this->_formatResponse(1,'添加地址成功');
                }else{
                    $this->_formatResponse(2,'添加地址失败');
                }
            }else{
                $this->_formatResponse(4,'收货地址最多10条');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    public function actionAddressUpdate()
    {
        $post = Yii::$app->request->post();
        if(isset($post['id'])){
            $modelClass = $this->modelClass;
            $model = $modelClass::findOne($post['id']);
            $model->loadValue($post);
            if($model->save()){
                $this->_formatResponse(1,'更新地址成功');
            }else{
                $this->_formatResponse(2,'更新地址失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

}
