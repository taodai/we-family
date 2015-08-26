<?php

namespace app\modules\content\controllers;

use common\library\tool;
use common\models\AppCategory;
use common\models\UploadForm;
use Yii;
use linslin\yii2\curl;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class CategoryController extends Controller
{
    public $enableCsrfValidation = false;

    public function init(){
        $this->layout = 'layouts';
    }
    public function actionIndex()
    {
        //获取顶级分类
        $module = Yii::$app->request->get('module');
        $model = new AppCategory();
        $result = $model->find()->where(['pid'=>0,'module'=>$module,'status'=>0])->orderBy(['rank'=>SORT_ASC])->asArray()->all();
        return $this->render('index',['category'=>$result,'module'=>$module]);
    }

    public function actionData(){
        $module = Yii::$app->request->post('module');
        $model = new AppCategory();
        //分页
        //$total = $model->find()->where(['module'=>$module,'status'=>0])->count();
        //$page = new Pagination(['totalCount' =>$total, 'pageSize' => Yii::$app->request->post('rows')]);
        //$page->page = Yii::$app->request->post('page')-1;
        //$result = $model->find()->where(['module'=>$module,'status'=>0])->orderBy(['rank'=>SORT_ASC,'id'=>SORT_DESC])->offset($page->offset)->limit($page->limit)->asArray()->all();
        $result = $model->find()->where(['module'=>$module,'status'=>0])->orderBy(['rank'=>SORT_ASC,'id'=>SORT_DESC])->asArray()->all();
        $category = tool::node_merge($result);
        $returnjson = [
            //'total'=>$total,
            'rows'=>$category
        ];
        echo Json::encode($returnjson);
    }
    public function actionAdd(){
        $data = Yii::$app->request->post();
        if(empty($data['id'])){
            unset($data['id']);
            $model = new AppCategory();
        }else{
            $model = AppCategory::findOne($data['id']);
        }
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            echo 1;
        }else{
            echo 0;
        }
    }
    public function actionEdit(){
        $id = Yii::$app->request->post('id');
        $model = new AppCategory();
        $result = $model->find()->where(['id'=>$id])->asArray()->one();
        echo Json::encode($result);
    }

    //排序
    public function actionRank(){
        $rank = Yii::$app->request->post('rank');
        if($rank){
            foreach($rank as $key=>$value){
                if(!is_numeric($value)) continue;
                $model = AppCategory::findOne($key);
                $model->rank = $value;
                $model->save();
            }
            echo 1;
        }
    }
//软删除
    public function actionRemove(){
        if(Yii::$app->request->isAjax){
            $id = \Yii::$app->request->post('ids');
            $model = new AppCategory();
            $child = $model->find()->where(['pid'=>$id,'status'=>0])->orderBy(['rank'=>SORT_ASC])->asArray()->all();
            if($child){
                $data['status'] = 0;
                $data['info'] = '删除失败！请先删除子分类';
            }else{

                $result = $model->updateAll(['status'=>1],['id'=>$id]);
                if($result){
                    $data['status'] = 1;
                    $data['info'] = '删除成功';
                }
            }

            echo Json::encode($data);
        }
    }
//硬删除
//    public function actionDel(){
//        if(Yii::$app->request->isAjax){
//            $id['id'] = Yii::$app->request->post('ids');
//            if(is_array($id)){
//                $result = AppCategory::deleteAll($id);
//                echo $result;
//            }else{
//                echo 0;
//            }
//
//        }
//    }



}
