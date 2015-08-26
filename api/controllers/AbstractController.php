<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class AbstractController extends ActiveController
{
    public $modelClass = '';

    /*错误码*/
    public $_code;
    /*错误信息*/
    public $_msg;
    /*GET 或者 POST 参数*/
    public $params;

    public function _formatResponse($code,$msg,$data = null,$page = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = array(
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
            'page' => $page
        );
        // die();
    }

    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        $this->params = empty(Yii::$app->request->get()) ? Yii::$app->request->post() : Yii::$app->request->get();
        return true;
    }

}
