<?php

namespace app\modules\system;

use Yii;

class system extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\system\controllers';
    public $defaultRoute = 'system';
    public $layout = false;

    public function init()
    {
        parent::init();
        Yii::setAlias('@sysViews', dirname(dirname(__DIR__)) . '/modules/system/views');
        if(Yii::$app->session->get('manager')){
            return true;
        }else{
            exit;
        }
        // custom initialization code goes here
    }
}
