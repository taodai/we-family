<?php

namespace api\modules\cart\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
// use common\models\User;

class CartController extends AbstractController
{

    public $modelClass = 'common\models\Cart';
    public $session = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    // public $params;

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
    /**
    * 自定义方法开启 
    */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);

        return $actions;
    }

    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        $this->params = empty(Yii::$app->request->get()) ? Yii::$app->request->post() : Yii::$app->request->get();
        return true;
    }

    // 添加商品至购物车
    public function actionAddProduct()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid']) && isset($post['pro_id']) && isset($post['nums'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->addProduct($post);
            if($response){
                $this->_formatResponse(1,'添加成功');
            }else{
                $this->_formatResponse(2,'添加失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //更新购物车信息（只可更新商品数量）
    public function actionUpdateProduct()
    {
        $post = Yii::$app->request->post();
        if(isset($post['cart_id']) && isset($post['nums'])){
            $modelClass = new $this->modelClass;
            $model = $modelClass::findOne($post['cart_id']);
            $model->nums = $post['nums'];
            $response = $model->save();
            if($response){
                $this->_formatResponse(1,'更新成功');
            }else{
                $this->_formatResponse(2,'更新失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //查询用户购物车信息
    public function actionGetCart()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAll($post['uid']);
            if($response){
                $this->_formatResponse(1,'获取购物车数据成功',$response);
            }else{
                $this->_formatResponse(2,'获取购物车数据失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //购物车信息删除
    public function actionDelProduct()
    {
        if($this->params['cart_id']){
            $modelClass = new $this->modelClass;
            $response = $modelClass->deleteAll('uid='.$this->params['uid'].' and cart_id in ('.$this->params['cart_id'].')');
            if($response){
                $this->_formatResponse(1,'删除购物车数据成功');
            }else{
                $this->_formatResponse(2,'删除购物车数据失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }
}
