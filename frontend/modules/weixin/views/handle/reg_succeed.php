<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>会员注册</title>
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/css/style-hql.css">
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container-fluid back-bluer">

	<div><img src="<?php echo Url::base(); ?>/image/succeed-1.jpg" width="100%" /></div>
	<div class="tx-c white student-id">学号：<?php echo $studyid; ?>（请备注您的学号）</div>
	<div><img src="<?php echo Url::base(); ?>/image/succeed-2.jpg" width="100%" /></div>
	<div><img src="<?php echo Url::base(); ?>/image/succeed-3.jpg" width="100%" /></div>
	<div><img src="<?php echo Url::base(); ?>/image/succeed-4.jpg" width="100%" /></div>
	<div><img src="<?php echo Url::base(); ?>/image/succeed-5.jpg" width="100%" /></div>
	<div><img src="<?php echo Url::base(); ?>/image/succeed-6.jpg" width="100%" /></div>
	<div><a href="<?php echo Url::toRoute(['menbercenter','code'=>$weixincode]); ?>"><img src="<?php echo Url::base(); ?>/image/enter.jpg" width="100%" /></a></div>

</div>

</body>
</html>