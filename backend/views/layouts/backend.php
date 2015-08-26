
<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
$this->registerJsFile("@web/theme/easyui131/bianjie_staly.js",['position'=>View::POS_BEGIN]); 
$this->registerJsFile("@web/theme/easyui131/jquery.edatagrid.js",['position'=>View::POS_BEGIN]); 
$this->registerJsFile("@web/theme/system/helper/dlg_helper.js",['position'=>View::POS_BEGIN]); 
$this->registerCssFile("@web/theme/system/helper/style.css",['position'=>View::POS_END]); 
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>   
</head>
<body onkeydown="BindEnter(event)">
<?php $this->beginBody() ?>
        <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>