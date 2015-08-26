<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!-- <!DOCTYPE html> -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>   
</head>
<body class="easyui-layout">
<?php $this->beginBody() ?>
        <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>