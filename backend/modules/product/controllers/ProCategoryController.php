<?php

namespace app\modules\product\controllers;

use common\library\tool;
use common\models\AppCategory;
use common\models\ProCategory;
use Yii;
use linslin\yii2\curl;
use yii\helpers\Json;
use yii\web\Controller;


class ProCategoryController extends Controller
{
    public $enableCsrfValidation = false;

    public function init(){
        $this->layout = false;
    }


    public function actionIndex()
    {
        //获取顶级分类
//        $module = Yii::$app->request->get('module');
//        $model = new AppCategory();
//        $result = $model->find()->where(['pid'=>0,'module'=>$module,'status'=>0])->orderBy(['rank'=>SORT_ASC])->asArray()->all();
        return $this->render('index');
    }

    public function actionData(){
        $model = new ProCategory();
        $result = $model->find()->where(['pc_status'=>1])->orderBy(['pc_id'=>SORT_DESC])->asArray()->all();
        $category = $model->node_merge($result);
        $returnjson = [
            'rows'=>$category
        ];
        echo Json::encode($category);
    }

    public function actionGetCate(){
        $model = new ProCategory();
        $result = $model->find()->select(['pc_id as id','pc_name as text','pc_fid'])->where(['pc_status'=>1,'pc_fid'=>0])->orderBy(['pc_id'=>SORT_DESC])->asArray()->all();
        $response = array_merge(array(0=>array('id'=>0,'text'=>'顶级分类')),$result);
        //$response = array(array('id'=>0,'text'=>'顶级分类','children'=>$result));
        echo Json::encode($response);
    }

    public function actionAdd(){
        $data = Yii::$app->request->post();
        $data['pc_id'] = Yii::$app->request->get('id');
        $data['pc_fid']=$data['pc_fid']?$data['pc_fid']:0;
        if(empty($data['pc_id'])){
            unset($data['pc_id']);
            $model = new ProCategory();
        }else{
            $model = ProCategory::findOne($data['pc_id']);
        }
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            $out = [
                'success'=>true
            ];

        }else{
            $out = [
                'success'=>false,
                'errorMsg'=>'数据有误，请检查'
            ];
        }
        echo Json::encode($out);
    }

//逻辑删除
    public function actionRemove(){
        if(Yii::$app->request->isAjax){
            $id = \Yii::$app->request->post('id');
            $model = new ProCategory();
            $child = $model->find()->where(['pc_fid'=>$id,'pc_status'=>1])->asArray()->all();
            if($child){
                $data['success'] = false;
                $data['errorMsg'] = '删除失败！请先删除子分类';
            }else{
                $result = $model->updateAll(['pc_status'=>0],['pc_id'=>$id]);
                if($result){
                    $data['success'] = true;
                }
            }

            echo Json::encode($data);
        }
    }

}
