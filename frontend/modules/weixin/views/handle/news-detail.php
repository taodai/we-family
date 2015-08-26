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
<body class="back-white">

<div class="container-fluid">
	<div class="pa-l pa-r">
		<h3><?php echo $news->title; ?></h3>
		<p>
			<small class="grayer"><?php echo date("Y-m-d H:i:s",$news->addtime) ?></small>
			<a class="text-primary" href="weixin://profile/gh_b37f4e5d297c">点乔教育</a>
		</p>
		<p><img src="<?php echo $news->picUrl ?>" width="100%" /></p>
		<div class="gray"><?php echo $news->content; ?></div>
	</div>

</div>

</body>
</html>