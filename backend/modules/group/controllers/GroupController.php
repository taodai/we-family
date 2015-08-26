<?php

namespace app\modules\group\controllers;

use Yii;
use yii\web\Controller;
use common\models\Group;

class GroupController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('@groupViews/group');
    }

    public function actionGroupList()
    {

        $post = Yii::$app->request->post();
        $page = isset($post['page']) ? $post['page'] : 1;
        $rows = isset($post['rows']) ? $post['rows'] : 20;

        $query = Group::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        $queryArr = ['group_id',
                    'group_name',
                    'group_type',
                    'group_icon_path',
                    'group_desc',
                    'group_creater_user',
                    'group_creater_manager',
                    'group_time',
                    'group_tag',
                    'is_public',
                    'is_recommend',
                    'group_status'];
        $models = $query->select('gt_id,gt_name,gt_status,gt_creater,gt_time,{{%manager}}.login_name as name')
            ->joinWith('manager')
            ->offset($rows*($page-1))
            ->limit($rows)
            ->asArray()
            ->all();
        $list = ['total'=>$total,'rows'=>$models];
        echo json_encode($list);
    }

    public function actionGroupAdd()
    {
        $post = Yii::$app->request->post();
        $model = new Group();
        $model->loadValue( Yii::$app->request->post() );
        $model->gt_time = time();
        $model->gt_creater = Yii::$app->session->get('manager')->mid;
        if( $model->save() ) {
            $out['success'] = true;
        }else{
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionGroupEdit()
    {
        $get = Yii::$app->request->get();
        $gt_id = $get['gt_id'];
        $model = Group::findOne($gt_id);
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

    public function actionGroupDel()
    {
        $post = Yii::$app->request->post();
        $gt_id = $post['gt_id'];
        $model = Group::findOne($gt_id);
        if ( $model == null ) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->gt_status = 2;
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
        $type = new Group();
        $model = $type->getType();
        echo json_encode($model);
    }
}
