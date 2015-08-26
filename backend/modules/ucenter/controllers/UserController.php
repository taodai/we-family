<?php

namespace app\modules\ucenter\controllers;

use Yii;
use yii\web\Controller;
use linslin\yii2\curl;
use common\models\User;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@ucenterViews/user');
    }

    public function actionUserList()
    {
        // $return = Member::find()->asArray()->all();
        // echo json_encode($return);
        $post = Yii::$app->request->post();
        $page = isset($post['page']) ? $post['page'] : 1;
        $rows = isset($post['rows']) ? $post['rows'] : 20;
        $query = User::find();
        foreach ($post as $key => $value) {
            switch ($key) {
                case 'uname':
                    if($value){
                        $query->where(['like',"{{%user}}.".$key,$value]);
                    }
                    break;
                case 'studyid':
                    if($value){
                        $query->andWhere(['like',"{{%user}}.".$key,$value]);
                    }
                    break;
                case 'in_crowd':
                    if($value){
                        $query->andWhere(["{{%user}}.".$key=>$value]);
                    }
                    break;                    
            }
        }
        $countQuery = clone $query;
        $total = $countQuery->count();
        // $response = $query->joinWith('info')->joinWith('agency')
        $response = $query->joinWith('info')
            ->offset($rows*($page-1))
            ->limit($rows)
            ->orderBy(['regtime'=>SORT_DESC])
            ->asArray()
            ->all();
            // $model->getAgencyByUid(99);
        foreach ( $response as $key => $value ) {
            if( is_array($value) ){
                // $model = new User();
                // $model->getAgencyByUid($value['uid']);
                $response[$key]['regtime'] = date('Y-m-d H:i:s',$value['regtime']);
                $response[$key]['last_login'] = !empty($value['last_login']) ? date('Y-m-d H:i:s',$value['last_login']) : '';
                $response[$key]['uname'] = substr($response[$key]['uname'], 0,5)."****".substr($response[$key]['uname'], -2);
                unset($response[$key]['password']);
                unset($response[$key]['auth_key']);
                $response[$key]['babyName'] = $value['info']['babyName'];
                // $response[$key]['first_agency'] = $value['agency']['first_agency'];
                // $response[$key]['second_agency'] = $value['agency']['second_agency'];
            }
        }
        $list = ['total'=>$total,'rows'=>$response];
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

    public function actionAddAllAgency()
    {
        $query = User::find()->asArray()->all();
        foreach ($query as $key => $value) {
            $model = new User();
            $model->getAgencyByUid($value['uid']);
        }
    }

    public function actionCrowd()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid']) && $post['uid']){
            $user = User::findOne($post['uid']);
            $user->in_crowd = 2 ;
            if($user->save()){
                $out['suc'] = true;
            }else{
                $out['errorMsg'] = '数据有误，请检查';
            }
        }
        echo json_encode($out);
    }

    public function actionUserAdd()
    {
        $post = Yii::$app->request->post();
        $model = new User();
        $model->loadValue( Yii::$app->request->post() );
        $model->setPassword($model->mobile);
        $model->create_uid = Yii::$app->session->get('Member')->uid;
        $model->createtime = time();
        if( $model->save() ) {
            $out['suc'] = true;
        }else{
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }


    public function actionUserEdit()
    {
        $get = Yii::$app->request->get();
        $uid = $get['uid'];
        $model = User::findOne($uid);
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

    public function actionUserDel()
    {
        $post = Yii::$app->request->post();
        $uid = $post['uid'];
        $model = User::findOne($uid);
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
