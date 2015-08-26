<?php

namespace app\modules\content;

use Yii;

class content extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\content\controllers';
    public $defaultRoute = 'content';
    public $layout = true;

    public function init()
    {
        parent::init();
        Yii::setAlias('@sysViews', dirname(dirname(__DIR__)) . '/modules/content/views');
        if(Yii::$app->session->get('manager')){
            return true;
        }else{
            exit;
        }
        // custom initialization code goes here
    }
}
