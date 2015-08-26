<?php

namespace api\modules\wiki;

class wikiModule extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\wiki\controllers';
    public $defaultRoute = 'wiki';
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
