<?php

namespace app\modules\app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\AppWiki;
use common\models\AppTips;

class HandleController extends Controller
{
    public $enableCsrfValidation = false;
    public function beforeaction($object)
    {
        $ajaxAction=array('WikiData');
        $action=substr($object->actionMethod,6);
        if(in_array($action,$ajaxAction)){
            header("Access-Control-Allow-Origin: *");
        }
        return true;
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionWikiData()
    {
    	$tips=AppTips::find()->where(['month'=>'7'])->one();
    	$arr=array('notice'=>$tips->notice);
        $tipsArr=array($arr);
		echo Json::encode($tipsArr);
    }
}
?>