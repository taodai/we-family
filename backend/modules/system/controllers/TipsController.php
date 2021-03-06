<?php
namespace app\modules\system\controllers;

use Yii;
use yii\web\Controller;
use common\models\AppTips;
use yii\helpers\Url;
use linslin\yii2\curl;

class TipsController extends Controller
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
        return $this->render('@sysViews/tips');
    }

    public function actionTipsList()
    {
        
        $post = Yii::$app->request->post();
        $page = $post['page'] ? $post['page'] : 1;
        $rows = $post['rows'] ? $post['rows'] : 10;
        $num=$rows*($page-1);
        
        
        $query = AppTips::find();
        $countQuery = clone $query;
        $total = $countQuery->count();
        $response = $query->offset($num)
            ->orderBy('number')
            ->limit($rows)
            ->asArray()
            ->all();
        //$model = new AppTips();
        
        //$response = $model->getAll($num);
        for($i=0;$i<count($response);$i++){
            $montharr=explode(',',$response[$i]['month']);
            $response[$i]['month']=$montharr[0];
            $month=$response[$i]['month'];
            if($month>=47){
                $str="学龄前(3-6岁)：第".($month-10)."个月";
            }else if($month>=23){
                $str="婴幼儿(1-3岁)：第".($month-10)."个月";
            }else if($month>=11){
                $str="新生儿(0-1岁)：第".($month-10)."个月";
            }else if($month==0){
                $str="备孕期";
            }else{
                $str="怀孕期：第".$month."个月";
            }
            $response[$i]['montharea']=$str;
        }
        //print_r($response);exit;
        $list = ['total'=>$total,'rows'=>$response];
        echo json_encode($list);
    }

    public function actionTipsAdd()
    {
    	//echo "asas";exit;
        $post = Yii::$app->request->post();
        $model = new AppTips();
        if($post['month']>=100){
            $out['errorMsg'] = '请选择具体的月份或月份区间';
        }else{
            $tips=AppTips::find()->where(['number'=>$post['month']])->one();
            if(!empty($tips)){
                $out['errorMsg'] = '请勿重复添加月份提示';
            }else{
                $post['number']=$post['month'];
                // if($post['month']>=47){
                //     for($i=$post['month'];$i<($post['month']+6);$i++){
                //         $montharr[$i]=$i;
                //     }
                //     $str=implode(',',$montharr);
                // }else if($post['month']>=23){
                //     for($i=$post['month'];$i<($post['month']+3);$i++){
                //         $montharr[$i]=$i;
                //     }
                //     $str=implode(',',$montharr);
                // }else{
                //     $str=$post['month'];
                // }
                // $post['month']=$str;

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
        if($post['month']>=100){
            $out['errorMsg'] = '请选择具体的月份或月份区间';
        }else{
        //print_r($post['month']);exit;
            $tips=AppTips::find()->where(['number'=>$post['month']])->andOnCondition("id !=".$get['id'])->one();
            if(!empty($tips)){
                $out['errorMsg'] = '请勿重复添加月份提示';
            }else{
                $mid = $get['id'];
                $model = AppTips::findOne($mid);
                $post['number']=$post['month'];
                // if($post['month']>=47){
                //     for($i=$post['month'];$i<($post['month']+5);$i++){
                //         $montharr[$i]=$i;
                //     }
                //     $str=implode(',',$montharr);
                // }else if($post['month']>=23){
                //     for($i=$post['month'];$i<($post['month']+3);$i++){
                //         $montharr[$i]=$i;
                //     }
                //     $str=implode(',',$montharr);
                // }else{
                //     $str=$post['month'];
                // }
                // $post['month']=$str;
                
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
    public function actionGetMonth(){
        $month=AppTips::getmonths();
        //echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        //print_r($month);exit;
        echo json_encode($month);
    }
}
?>