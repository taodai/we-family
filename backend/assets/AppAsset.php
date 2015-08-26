<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'theme/easyui131/themes/default/easyui.css',
        'theme/easyui131/themes/icon.css',
    ];
    public $js = [
        'theme/easyui131/jquery-1.8.0.min.js',
        'theme/easyui131/jquery.easyui.min.js',
        'theme/easyui131/locale/easyui-lang-zh_CN.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset', //
    ];
    public $jsOptions = [
        'position'=> View::POS_HEAD,
    ];
    // public $cssOptions = [
    //     'position'=> View::POS_HEAD,
    // ];
}
