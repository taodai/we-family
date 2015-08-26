<?php

namespace app\modules\ucenter\controllers;

use Yii;
use yii\web\Controller;
use linslin\yii2\curl;
use common\models\Member;

class MemberController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@ucenterViews/member');
    }

    public function actionMemberList()
    {
        // $return = Member::find()->asArray()->all();
        // echo json_encode($return);

        $post = Yii::$app->request->post();
        $page = $post['page'] ? $post['page'] : 1;
        $rows = $post['rows'] ? $post['rows'] : 20;

        $query = Member::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        $models = $query->offset($rows*($page-1))
            ->limit($rows)
            ->orderBy(['userid'=>'ASC'])
            ->asArray()
            ->all();
        foreach ( $models as $key => $value ) {
            if( is_array($value) ){

                $models[$key]['regtime'] = date('Y-m-d H:i:s',$value['regtime']);
                $models[$key]['logintime'] = date('Y-m-d H:i:s',$value['logintime']);
            }
        }
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

    public function actionMemberAdd()
    {
        $post = Yii::$app->request->post();
        $model = new Member();
        $model->loadValue( Yii::$app->request->post() );
        $model->setPassword($model->mobile);
        $model->create_userid = Yii::$app->session->get('Member')->userid;
        $model->createtime = time();
        if( $model->save() ) {
            $out['suc'] = true;
        }else{
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionMemberEdit()
    {
        $get = Yii::$app->request->get();
        $userid = $get['userid'];
        $model = Member::findOne($userid);
        if ($model === null) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->loadValue(Yii::$app->request->post());
            if ($model->save()) {
                $out['suc'] = true;
            }
        }
        echo json_encode($out);
    }

    public function actionMemberDel()
    {
        $post = Yii::$app->request->post();
        $userid = $post['userid'];
        $model = Member::findOne($userid);
        if ( $model == null ) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->status = 2;
            if ( $model->save() ) {
                $out['suc'] = true;
            }else{
                $out['errorMsg'] = '数据保存失败，请检查';
            }
        }
        echo json_encode($out);
    }

    public function actionGetArea()
    {
        $model = new \common\models\Area();
        $response = $model->getPrivionArray();
        // var_dump($response);
        echo json_encode($response);
    }
}
