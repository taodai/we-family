<?php

namespace app\modules\app;

class app extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\app\controllers';
    public $defaultRoute = 'handle';
    
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
