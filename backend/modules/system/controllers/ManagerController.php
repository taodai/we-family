<?php

namespace app\modules\system\controllers;

use Yii;
use backend\models\Manager;
use yii\helpers\Url;
use linslin\yii2\curl;

class ManagerController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@sysViews/manager');
    }

    public function actionManagerList()
    {
        $model = new Manager();
        $response = $model->getAll();
        foreach ( $response as $key => $value ) {
            if( is_array($value) ){

                $response[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
            }
        }
        echo json_encode($response);
    }

    public function actionManagerAdd()
    {
        $post = Yii::$app->request->post();
        $model = new Manager();
        $model->loadValue( Yii::$app->request->post() );
        $model->setPassword($model->mobile);
        $model->create_mid = Yii::$app->session->get('manager')->mid;
        $model->createtime = time();
        if( $model->save() ) {
            $out['success'] = true;
        }else{
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionManagerEdit()
    {
        $get = Yii::$app->request->get();
        $mid = $get['id'];
        $model = Manager::findOne($mid);
        if ($model === null) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->loadValue(Yii::$app->request->post());
            if ($model->save()) {
                $out['success'] = true;
            }
        }
        echo json_encode($out);
    }

    public function actionManagerDel()
    {
        $post = Yii::$app->request->post();
        $mid = $post['id'];
        $model = Manager::findOne($mid);
        if ( $model == null ) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->status = ($model->status == 1) ? 2 : 1;
            if ( $model->save() ) {
                $out['success'] = true;
            }else{
                $out['errorMsg'] = '数据保存失败，请检查';
            }
        }
        echo json_encode($out);
    }

}
