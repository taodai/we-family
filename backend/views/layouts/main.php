<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
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
<body class="bg-black" onkeydown="BindEnter(event)">
<?php $this->beginBody() ?>
<div class="wrap">

    <div class="container">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
<div id="login_bottom">
<p><b>点精教育移动终端软件管理平台</b> Version 1.0 由浙江点精提供技术支持!</p>
</div>
</body>
</html>
<?php $this->endPage() ?>
