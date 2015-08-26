<?php

namespace app\modules\product\controllers;

use Yii;
use yii\web\Controller;
use common\models\ProTags;

class TagController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        $this->layout = false;
    }
    public function actionIndex()
    {
        return $this->render('@proViews/tag');
    }

    public function actionTagList()
    {

        $post = Yii::$app->request->post();
        $page = isset($post['page']) ? $post['page'] : 1;
        $rows = isset($post['rows']) ? $post['rows'] : 20;

        $query = ProTags::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        $models = $query->offset($rows*($page-1))
            ->limit($rows)
            ->orderBy(['pt_id'=>'ASC'])
            ->asArray()
            ->all();
        foreach ($models as $key => $value) {
            if( is_array($value) ){
                $models[$key]['pt_time'] = date('Y-m-d H:i:s',$value['pt_time']);
            }
        }
        $list = ['total'=>$total,'rows'=>$models];
        echo json_encode($list);

    }

    public function actionTagAdd()
    {
        $post = Yii::$app->request->post();
        $model = new ProTags();
        $model->loadValue( Yii::$app->request->post() );
        $model->pt_time = time();
        if( $model->save() ) {
            $out['suc'] = true;
        }else{
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionTagEdit()
    {
        $get = Yii::$app->request->get();
        $pt_id = $get['pt_id'];
        $model = ProTags::findOne($pt_id);
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

    public function actionTagDel()
    {
        $post = Yii::$app->request->post();
        $pt_id = $post['pt_id'];
        $model = ProTags::findOne($pt_id);
        if ( $model == null ) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $model->pt_status = 2;
            if ( $model->save() ) {
                $out['suc'] = true;
            }else{
                $out['errorMsg'] = '数据保存失败，请检查';
            }
        }
        echo json_encode($out);
    }

    public function actionGetTag()
    {
        $Tag = new ProTags();
        $model = $Tag->getTag();
        echo json_encode($model);
    }
}
