<?php

namespace app\modules\ucenter\controllers;

use Yii;
use yii\web\Controller;
// use linslin\yii2\curl;
use common\models\UserType;

class TypeController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@ucenterViews/type');
    }

    public function actionTypeList()
    {
        // $return = Member::find()->asArray()->all();
        // echo json_encode($return);

        $post = Yii::$app->request->post();
        $page = $post['page'] ? $post['page'] : 1;
        $rows = $post['rows'] ? $post['rows'] : 20;

        $query = UserType::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        $models = $query->offset($rows*($page-1))
            ->limit($rows)
            ->orderBy(['ut_id'=>'ASC'])
            ->asArray()
            ->all();
        $list = ['total'=>$total,'rows'=>$models];
        echo json_encode($list);

        /*远程API访问
        $post = Yii::$app->request->post();
        $page = $post['page'];

        $endUrl = ($page > 1) ? '?page='.$page : '';
        $curl = new curl\Curl();
        $apiUrl = Yii::$app->params['apiUrl'].Yii::$app->urlManager->createUrl(['memberApi/member']).$endUrl;
        $curl->get($apiUrl);
        echo $curl->response;*/


        // $model = new Member();
        // $response = $model->getAll();
        // foreach ( $response as $key => $value ) {
        //     if( is_array($value) ){

        //         $response[$key]['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
        //     }
        // }
        // echo json_encode($response);
    }

    public function actionTypeAdd()
    {
        $post = Yii::$app->request->post();
        $model = new UserType();
        $model->loadValue( Yii::$app->request->post() );
        $model->ut_createtime = time();
        if( $model->save() ) {
            $out['success'] = true;
        }else{
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionTypeEdit()
    {
        $get = Yii::$app->request->get();
        $ut_id = $get['ut_id'];
        $model = UserType::findOne($ut_id);
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

    public function actionTypeDel()
    {
        $post = Yii::$app->request->post();
        $ut_id = $post['ut_id'];
        $model = UserType::findOne($ut_id);
        if ( $model == null ) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->mt_status = 2;
            if ( $model->save() ) {
                $out['success'] = true;
            }else{
                $out['errorMsg'] = '数据保存失败，请检查';
            }
        }
        echo json_encode($out);
    }

    public function actionGetType()
    {
        $type = new UserType();
        $model = $type->getType();
        echo json_encode($model);
    }
}
