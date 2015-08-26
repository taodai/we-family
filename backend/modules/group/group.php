<?php

namespace app\modules\group;

use Yii;

class group extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\group\controllers';
    public $layout = false;

    public function init()
    {
        parent::init();
        Yii::setAlias('@groupViews', dirname(dirname(__DIR__)) . '/modules/group/views');

        // custom initialization code goes here
    }
}
