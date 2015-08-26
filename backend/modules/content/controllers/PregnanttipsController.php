<?php
namespace app\modules\content\controllers;

use Yii;
use yii\web\Controller;
use common\models\AppPregnanttips;
use yii\helpers\Url;
use linslin\yii2\curl;

class PregnanttipsController extends Controller
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
        return $this->render('pregnanttips');
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

        $query = AppPregnanttips::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        //echo $rows*($page-1);exit;
        $response = $query->offset($rows*($page-1))
            ->orderBy('day')
            ->limit($rows)
            ->asArray()
            ->all();
        //echo count($response);exit;
        //print_r($response);exit;
        // $model = new AppWarmtips();
        // $response = $model->getAll($num);
        $weeks=AppPregnanttips::getweeks();
        if(!empty($response)){
            for($i=0;$i<count($response);$i++){
                $day=$response[$i]['day'];
                $did=$day%7;
                $wid=intval($day/7);
                $dayarr=$weeks[$wid]['children'];
                if($day<7){
                    $text=$dayarr[($did-1)]['text'];
                }else{
                    $text=$dayarr[$did]['text'];
                }
                
                
                
                
                $response[$i]['pregnantdays']=280-$day;
                $response[$i]['week']=$text;
            }
        }
        $list = ['total'=>$total,'rows'=>$response];
        // print_r($response);exit;
        echo json_encode($list);
    }

    public function actionTipsAdd()
    {
        $post = Yii::$app->request->post();
        $model = new AppPregnanttips();
        if($post['day']>=1000||$post['day']==0){
            $out['errorMsg'] = '请选择具体的天数';
        }else{
            $tips=AppPregnanttips::find()->where(['day'=>$post['day']])->one();
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
        if($post['day']>=1000||$post['day']==0){
            $out['errorMsg'] = '请选择具体的天数';
        }else{
        //print_r($post['month']);exit;
            $tips=AppPregnanttips::find()->where(['day'=>$post['day']])->andOnCondition("id !=".$get['id'])->one();
            if(!empty($tips)){
                $out['errorMsg'] = '请勿重复添加天数提示';
            }else{
                $mid = $get['id'];
                $model = AppPregnanttips::findOne($mid);
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


    public function actionGetWeeks(){
        $weeks=AppPregnanttips::getweeks();
        //echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        //print_r($weeks);exit;
        echo json_encode($weeks);
    }
}
?>