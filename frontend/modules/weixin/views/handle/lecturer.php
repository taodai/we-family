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
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/members.js"></script>

</head>
<body>

<div class="container-fluid">
	
	<div class=""><img src="<?php echo $lec->lecturer_pic; ?>" width="100%"></div>
	
	<div class="pa-l pa-r">
		<div class="page-header"><h3>专家介绍</h3></div>
		<p class="text-muted f18"><?php echo $lec->le_desc; ?></p>
		<div class="sr-only">
			<div class="page-header arrange f18"><h3>课程安排</h3></div>
			<p class="text-muted"><a href="">2015年5月11日 0001期分享--幼小衔接，共助成长</a></p>
			<p class="text-muted"><a href="">ECEI哈奇乐微信课堂0002期：让孩子变好比让孩子变聪明更重要！</a></p>
			<p class="text-muted"><a href="">ECEI哈奇乐微信课堂0003期精彩回顾</a></p>
			<div class="page-header before f18"><h3>以往课程</h3></div>
			<p class="text-muted"><a href="">2015年5月11日 0001期分享--幼小衔接，共助成长</a></p>
			<p class="text-muted"><a href="">ECEI哈奇乐微信课堂0002期：让孩子变好比让孩子变聪明更重要！</a></p>
			<p class="text-muted"><a href="">ECEI哈奇乐微信课堂0003期精彩回顾</a></p>
		</div>
	</div>
	<br>

</div>

</body>
</html>