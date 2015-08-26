<?php

namespace backend\modules\apiManage\controllers;

use Yii;
use common\models\AppParams;
use yii\helpers\Url;
use linslin\yii2\curl;

class AppController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';  

    public function actionIndex()
    {
        return $this->render('@apiViews/app');
    }

    public function actionAppList()
    {
        $model = new AppParams();
        $response = $model->getAll();
        foreach ( $response as $key => $value ) {
            if( is_array($value) ){

                $response[$key]['time_on'] = date('Y-m-d H:i:s',$value['time_on']);
                $response[$key]['time_off'] = empty($value['time_off']) ? '' : date('Y-m-d H:i:s',$value['time_off']);
            }
        }
        echo json_encode($response);
    }

    public function actionAppAdd()
    {

        $post = Yii::$app->request->post();
        $model = new AppParams();
        $model->loadValue( $post );
        if($model->checkUniq('','appName',$model->appName)){ // 判断appName是否唯一 exp：checkUniq($primaryKey,$key,$value);
            $model->formatMsg('有相同的应用名称，请修改',false);
        }else{
            $model->appSecret = $this->randStr(12);
            $model->appId = strtolower(Yii::$app->params['appPrefix'].$this->randStr(12));
            $model->appKey = md5($model->appId.$model->appToken.$model->appSecret);
            $model->time_on = time();
            if( $model->save() ) {
                $model->formatMsg('',true);
            }else{
                $model->formatMsg('数据有误，请检查',false);
            }
        }
        echo json_encode($model->_out);
    }

    public function actionAppEdit()
    {
        $get = Yii::$app->request->get();
        $id = $get['id'];
        $post = Yii::$app->request->post();
        $check = new AppParams();
        $model = AppParams::findOne($id);
        if($check->checkUniq($id,'appName',$post['appName'])){
            $model->formatMsg('有相同的应用名称，请修改',false);
        }else{
            if ($model === null) {
                $model->formatMsg('非法ID或该行已删除',false);
            }else{
                $model->loadValue( $post );
                if ($model->save()) {
                    $model->formatMsg('',true);
                }
            }
        }
        echo json_encode($model->_out);
    }

    public function actionAppDel()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $model = AppParams::findOne($id);
        if ( $model == null ) {
            $model->formatMsg('非法ID或该行已删除',false);
        }else{
            $model->p_status = ($model->p_status == 1) ? 2 : 1;
            $model->time_off = ($model->p_status == 1) ? '' : time();
            if ( $model->save() ) {
                $model->formatMsg('',true);
            }else{
                $model->formatMsg('数据有误，请检查',false);
            }
        }
        echo json_encode($model->_out);
    }

    private function randStr( $length = 8) 
    {  
        $return = '';
        for ( $i = 0; $i < $length; $i++ )  
        {  
            $return .= $this->chars[ mt_rand(0, strlen($this->chars) - 1) ];  
        }  
        return $return;  
    } 

}
