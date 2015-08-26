<?php

namespace app\modules\content\controllers;

use common\library\tool;
use common\models\AppCategory;
use common\models\AppWiki;
use common\models\CourseNotice;
use common\models\CourseReview;
use common\models\Lecturer;
use common\models\UploadForm;
use Yii;
use linslin\yii2\curl;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class CourseNoticeController extends Controller
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
        $model = new CourseReview();
        $model_course = new CourseNotice();
        return $this->render('index',['model'=>$model,'model_course'=>$model_course]);
    }

    public function actionData(){
        $model = new CourseNotice();
        $total = $model->find()->where(['hql_course_notice.status'=>0])->count();
        $page = new Pagination(['totalCount' =>$total, 'pageSize' => $_POST['rows']]);
        $page->page = Yii::$app->request->post('page')-1;
        $result = $model->find()->joinWith('lecturer')->joinWith('courseReview')->where(['hql_course_notice.status'=>0])->orderBy(['createtime'=>SORT_DESC])->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($result as $key=>$value){
            $result[$key]['share_time'] = date('Y-m-d H:i:s',$value['share_time']);
        }
        $returnjson = [
            'total'=>$total,
            'rows'=>$result
        ];
        echo Json::encode($returnjson);
    }
    public function actionOne(){
        $cnid = Yii::$app->request->post('cnid');
        $model = new CourseNotice();
        $result = $model->find()->joinWith('lecturer')->joinWith('courseReview')->where(['hql_course_notice.cnid'=>$cnid])->asArray()->one();
        $result['share_time'] = date('Y-m-d H:i:s',$result['share_time']);
        echo Json::encode($result);
    }
    //获取讲师
    public function actionLecturer(){
        $model = new Lecturer();
        $lecturer = $model->find()->where(['le_status'=>0])->asArray()->all();
        $data = [];
        foreach($lecturer as $key=>$value){
            $data[$key]['id'] = $value['leid'];
            $data[$key]['text'] = $value['le_name'];
        }
        echo Json::encode($data);
    }

    public function actionReview(){
        $data = Yii::$app->request->post();

        if(empty($data['id'])){
            $data['reviewtime'] = time();
            unset($data['id']);
            $model = new CourseReview();
            $flag = 1;//新增
        }else{
            $model = CourseReview::findOne($data['id']);
            $flag = 2;//修改
        }
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            echo $flag;
        }else{
            foreach($model->getErrors() as $key=>$value){
                foreach($value as $k=>$v){
                    echo ($k+1).'.'.$v;
                }
            }
        }
    }

    public function actionAdd(){
        $data = Yii::$app->request->post();
        $data['status'] = 0;
        $data['share_time'] = strtotime($data['share_time']);
        $user = Yii::$app->session->get('manager');
        if(empty($data['cnid'])){
            $data['real_name'] = $user->realName;
            $data['createtime'] = time();
            unset($data['cnid']);
            $model = new CourseNotice();
            $flag = 1;//新增
        }else{
            $model = CourseNotice::findOne($data['cnid']);
            $flag = 2;//修改
        }
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            echo $flag;
        }else{

            foreach($model->getErrors() as $key=>$value){
                foreach($value as $k=>$v){
                    echo ($k+1).'.'.$v;
                }
            }
        }
    }


    //软删除
    public function actionRemove(){
        if(Yii::$app->request->isAjax){
            $id = \Yii::$app->request->post('cnid');
            $model = new CourseNotice();
            $result = $model->updateAll(['status'=>1],['cnid'=>$id]);
            echo $result?$result:0;
        }
    }

    public function actionSetmonth(){
        $month = [];
        $wiki_model = new AppWiki();
        $id = Yii::$app->request->get('id');
        $wiki = $wiki_model->find()->where(['id'=>$id])->asArray()->one();
        $tempArr = explode(',',$wiki);
        foreach ($month as $key=>$value){
            if(in_array($value['id'],$tempArr))$month[$key]['checked'] = true;
            foreach($value['children'] as $k=>$v){
                if(in_array($v['id'],$tempArr))$month[$key]['children'][$k]['checked'] = true;
            }
        }
    }

    public function actionGetmonth(){
        $month = $this->month;
        echo Json::encode($month);
    }



}
