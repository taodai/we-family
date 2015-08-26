<?php

namespace app\modules\ucenter;

use Yii;

class ucenter extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\ucenter\controllers';
    public $layout = false;

    public function init()
    {
        parent::init();
        Yii::setAlias('@ucenterViews', dirname(dirname(__DIR__)) . '/modules/ucenter/views');

        // custom initialization code goes here
    }
}
