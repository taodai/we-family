<?php 
namespace app\modules\weixin\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
class SiteController extends Controller
{
	public function actionErrorPage()
    {
        echo '<img style="padding: 0;" src="'.Url::base().'/image/404.jpg" width="100%" />';exit;
        return $this->render('error-page');
    }
}




 ?>