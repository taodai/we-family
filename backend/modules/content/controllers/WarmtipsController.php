<?php
namespace app\modules\content\controllers;

use Yii;
use yii\web\Controller;
use common\models\AppWarmtips;
use yii\helpers\Url;
use linslin\yii2\curl;

class WarmtipsController extends Controller
{
	public $enableCsrfValidation = false;

    public function beforeaction($object)
    {
        $ajaxAction=array('TipsList','TipsAdd','TipsEdit');
        $action=substr($object->actionMethod,6);
        if(in_array($action,$ajaxAction)){
            header("Access-Control-Allow-Origin: *");
        }
        return true;
    }
    public function actionIndex()
    {

        return $this->render('warmtips');
    }

    public function actionTipsList()
    {
        // if($_POST){
        //     print_r($_POST);exit;
        // }
        
        $post = Yii::$app->request->post();

        $page = $post['page'] ? $post['page'] : 1;
        $rows = $post['rows'] ? $post['rows'] : 20;
        $num=$rows*($page-1);

        $query = AppWarmtips::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        $response = $query->offset($rows*($page-1))
            ->orderBy('day')
            ->limit($rows)
            ->asArray()
            ->all();
        // $model = new AppWarmtips();
        // $response = $model->getAll($num);
        $month=AppWarmtips::getdays();
        if(!empty($response)){
            for($i=0;$i<count($response);$i++){
                $day=$response[$i]['day'];
                
                $did=($day%30)-1;
                if($did<0){
                    $did=29;
                    $mid=intval($day/30)-1;
                }else{
                    $mid=intval($day/30);
                }
                $dayarr=$month[$mid]['children'];
                $text=$dayarr[$did]['text'];
                $response[$i]['month']=$text;
            }
        }
        $list = ['total'=>$total,'rows'=>$response];
        // print_r($response);exit;
        echo json_encode($list);
    }

    public function actionTipsAdd()
    {
        $post = Yii::$app->request->post();
        $model = new AppWarmtips();
        if($post['day']>=20000||$post['day']==0){
            $out['errorMsg'] = '请选择具体的天数';
        }else{
            $tips=AppWarmtips::find()->where(['day'=>$post['day']])->one();
            if(!empty($tips)){
                $out['errorMsg'] = '请勿重复添加天数提示';
            }else{
                $model->loadValue($post);
                if( $model->save() ) {
                    $out['suc'] = true;
                }else{
                    $out['errorMsg'] = '数据有误，请检查';
                }
            }
        }
        //print_r(get_class_methods($model));exit;
        echo json_encode($out);
    }

    public function actionTipsEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        if($post['day']>=20000||$post['day']==0){
            $out['errorMsg'] = '请选择具体的天数';
        }else{
        //print_r($post['month']);exit;
            $tips=AppWarmtips::find()->where(['day'=>$post['day']])->andOnCondition("id !=".$get['id'])->one();
            if(!empty($tips)){
                $out['errorMsg'] = '请勿重复添加天数提示';
            }else{
                $mid = $get['id'];
                $model = AppWarmtips::findOne($mid);
                if ($model === null) {
                    $out['errorMsg'] = '请选择一行或该行不存在';
                }else{
                    $model->loadValue($post);
                    if ($model->save()) {
                        $out['suc'] = true;
                    }
                }
            }
        }
        echo json_encode($out);
    }


    public function actionGetDays(){
        $month=AppWarmtips::getdays();
        //echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        //print_r($month);exit;
        echo json_encode($month);
    }
}
?>