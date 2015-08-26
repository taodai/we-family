<?php

namespace backend\modules\apiManage\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        // return $this->render('index');
        return $this->render('@apiViews/manager');
    }
}
