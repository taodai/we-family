<?php

namespace app\modules\weixin;

class weixin extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\weixin\controllers';
    public $defaultRoute = 'handle';
    
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
