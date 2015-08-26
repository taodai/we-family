<?php

namespace backend\modules\product;

use Yii;

class product extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\product\controllers';
    public $defaultRoute = 'product';
    public $layout = true;

    public function init()
    {
        parent::init();
        Yii::setAlias('@proViews', dirname(dirname(__DIR__)) . '/modules/product/views');
        if(Yii::$app->session->get('manager')){
            return true;

        }else{
            exit;
        }
        // custom initialization code goes here
    }
}
