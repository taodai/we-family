<?php

namespace app\modules\system\controllers;

use Yii;
use backend\models\SysModule;
use yii\helpers\Url;

class ModuleController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@sysViews/module');
    }

    public function actionMoList()
    {
        $model = new SysModule();
        $response = $model->getAll();
        foreach ( $response as $key => $value ) {
            if( is_array($value) ){

                $response[$key]['mo_time'] = date('Y-m-d H:i:s',$value['mo_time']);
            }
        }
        echo json_encode($response);
    }

    public function actionMoAdd()
    {
        $model = new SysModule();
        $model->loadValue( Yii::$app->request->post() );
        $model->mo_time = time();
        if( $model->save() ) {
            $out['success'] = true;
        }else{
            $out['success'] = false;
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionMoEdit()
    {
        $get = Yii::$app->request->get();
        $moid = $get['id'];
        $model = SysModule::findOne($moid);
        if ($model === null) {
            $out['success'] = false;
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->loadValue(Yii::$app->request->post());
            if ($model->save()) {
                $out['success'] = true;
            }
        }
        echo json_encode($out);
    }

    public function actionMoDel()
    {
        $post = Yii::$app->request->post();
        $moid = $post['id'];
        $model = SysModule::findOne($moid);
        if ( $model == null ) {
            $out['success'] = false;
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->mo_status = 2;
            if ( $model->save() ) {
                $out['success'] = true;
            }else{
                $out['success'] = false;
                $out['errorMsg'] = '数据保存失败，请检查';
            }
        }
        echo json_encode($out);
    }

}
