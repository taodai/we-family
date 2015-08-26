<?php

namespace api\modules\agency\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\User;

class AgencyController extends AbstractController
{

    public $modelClass = 'common\models\Agency';
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
        return true;
    }

    //按级别查询代理信息
    public function actionGetAgency()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid']) && isset($post['level'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAgencyByParentId($post['uid'],$post['level']);
            if($response){
                $this->_formatResponse(1,'获取列表成功',$response);
            }else{
                $this->_formatResponse(2,'获取列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //查询用户所有代理信息
    public function actionGetAgencyAll()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAgencyAll($post['uid']);
            if($response){
                $this->_formatResponse(1,'获取列表成功',$response);
            }else{
                $this->_formatResponse(2,'获取列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }
}
