<?php

namespace app\modules\content\controllers;

use common\models\Lecturer;
use common\models\UploadForm;
use Imagine\Image\Color;
use Yii;
use linslin\yii2\curl;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\UploadedFile;

class LecturerController extends Controller
{
    public $enableCsrfValidation = false;

    public function init(){
        $this->layout = 'layouts';
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->request->hostInfo,//图片访问路径前缀
                    "imagePathFormat" => "/uploads/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }
    public function actionIndex()
    {
        $model = new Lecturer();

        return $this->render('index',['model'=>$model]);
    }



    public function actionData(){
        $model = new Lecturer();
        $total = $model->find()->count();
        $page = new Pagination(['totalCount' =>$total, 'pageSize' => $_POST['rows']]);
        $result = $model->find()->where(['le_status'=>0])->orderBy(['le_time'=>SORT_DESC])->asArray()->limit($page->limit)->all();
        foreach($result as $key=>$value){
            $result[$key]['le_time'] = date('Y-d-m H:i:s',$value['le_time']);
        }
        echo Json::encode($result);
    }
    //新增
    public function actionAdd(){
        $data = Yii::$app->request->post();
        $data['le_time'] = time();
        $data['le_status'] = 0;
        //$temp = explode('/',$data['le_pic']);
        //$data['le_picname'] = $temp[count($temp)-1];
        if(empty($data['leid'])){
            unset($data['leid']);
        }
        $model = new Lecturer();
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            echo 1;
        }else{
            foreach($model->getErrors() as $key=>$value){
                foreach($value as $k=>$v){
                    echo ($k+1).'.'.$v;
                }
            }
        }
    }
    //修改
    public function actionEdit(){
        $data = Yii::$app->request->post();
        $model = Lecturer::findOne($data['leid']);
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            echo 1;
        }else{
            echo 0;
        }
    }

    //软删除
    public function actionRemove(){
        if(Yii::$app->request->isAjax){
            $id = \Yii::$app->request->post('leid');
            $model = new Lecturer();
            $result = $model->updateAll(['le_status'=>1],['leid'=>$id]);
            echo $result?$result:0;
        }
    }


    public function actionUploadfile(){
        $param = Yii::$app->request->post('thumb');
        $act = Yii::$app->request->post('act');
        if($param){
            $dir = $param.'/';
            $filename = date('YmdHis').'-'.rand(10000,99999);
        }else{
            $dir = 'wiki/';
            $filename = date('YmdHis');
        }
        $upload = 'uploads/'.$dir; //主目录
        if($act){
            $subDir=$act;
        }else{

            $subDir = $upload.date('Ym').'/'; //子目录
        }
        $url = Yii::$app->request->hostInfo.'/';//访问网站前缀
        if(!file_exists($upload)){
            if(!is_dir($upload) || is_writable($upload)){
                if(!mkdir($upload)) $error['info']='主目录创建失败';
            }
        }
        if(!file_exists($subDir)) {
            if (!is_dir($subDir) || is_writable($subDir)) {
                if (!mkdir($subDir)) $error['info'] = '子目录创建失败';
            }
        }

        $model = new UploadForm();
        $model->file = UploadedFile::getInstance($model,'file');
        if($model->validate()){
            $pic = $filename.'.'.$model->file->extension;
            $model->file->saveAs($subDir.$filename.'.'.$model->file->extension);
            $path = $url.$subDir.$filename.'.'.$model->file->extension;
            $picUrl = $subDir.$filename.'.'.$model->file->extension;
            //Image::thumbnail($picUrl,640,320)->save($picUrl);
            if(Yii::$app->request->post('thumb')=='wiki'){
                Image::thumbnail($picUrl,640,320)->save($picUrl);
            }elseif(Yii::$app->request->post('thumb')=='products'){
                Image::thumbnail($picUrl,640,640)->save($picUrl);
            }
            $jsonArr = [
                'status'=>1,
                'url'=>$path,//完整路径
                'pic'=>$pic,//文件名
                'prefix'=>$subDir,
                'info'=>'图片上传成功'
            ];
        }else{
            $jsonArr = [
                'status'=>0,
                'info'=>'图片上传失败'
            ];
        }

        echo Json::encode($jsonArr);
    }


}
