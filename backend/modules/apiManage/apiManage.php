<?php

namespace backend\modules\apiManage;
use Yii;

class apiManage extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\apiManage\controllers';
    public $layout = false;

    public function init()
    {
        parent::init();
        Yii::setAlias('@apiViews', dirname(dirname(__DIR__)) . '/modules/apiManage/views');

        // custom initialization code goes here
    }
}
