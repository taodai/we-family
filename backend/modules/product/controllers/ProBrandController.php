<?php

namespace app\modules\product\controllers;

use common\models\ProBrand;
use common\models\ProCategory;
use Yii;
use linslin\yii2\curl;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;


class ProBrandController extends Controller
{
    public $enableCsrfValidation = false;

    public function init(){
        $this->layout = false;
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionData(){
        $model = new ProBrand();
        $total = $model->find()->count();
        $page = new Pagination(['totalCount' =>$total, 'pageSize' => $_POST['rows']]);
        $result = $model->find()->where(['pb_status'=>1])->orderBy(['pb_id'=>SORT_DESC])->offset($page->offset)->limit($page->limit)->asArray()->all();
        $returnjson = [
            'total'=>$total,
            'rows'=>$result
        ];
        echo Json::encode($returnjson);
    }

    public function actionAdd(){
        $data = Yii::$app->request->post();
        $data['pb_id'] = Yii::$app->request->get('id');
        if(empty($data['pb_id'])){
            unset($data['pb_id']);
            $data['pb_time'] = time();
            $model = new ProBrand();
        }else{
            $model = ProBrand::findOne($data['pb_id']);
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
            $model = new ProBrand();
            $result = $model->updateAll(['pb_status'=>0],['pb_id'=>$id]);
            if($result){
                $data['success'] = true;
            }else{
                $data = [
                    'success'=>false,
                    'errorMsg'=>'删除失败，请检查'
                ];
            }

            echo Json::encode($data);
        }
    }

}
