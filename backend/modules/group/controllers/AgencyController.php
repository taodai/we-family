<?php

namespace app\modules\ucenter\controllers;

use Yii;
use common\models\User;
use common\models\Agency;
use yii\helpers\Url;

class AgencyController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@ucenterViews/agency');
    }

    //代理数据
    public function actionAgencyList()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $page = isset($post['page']) ? $post['page'] : 1;
        $rows = isset($post['rows']) ? $post['rows'] : 20;
        $query = Agency::find()->where(['parent_id'=>$get['uid'],'level'=>$get['level']]);
        $countQuery = clone $query;
        $total = $countQuery->count();
        $response = $query->select(['agency_id','parent_id','child_id','level'])
            ->joinWith('info')
            ->offset($rows*($page-1))
            ->limit($rows)
            ->asArray()
            ->all();
        foreach ( $response as $key => $value ) {
            if( is_array($value) ){
                $response[$key]['uname'] = substr($value['info']['uname'], 0,5)."****".substr($value['info']['uname'], -2);
                $response[$key]['babyName'] = $value['info']['babyName'];
            }
        }
        $list = ['total'=>$total,'rows'=>$response];
        echo json_encode($list);
    }

}