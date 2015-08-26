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
	<div class="container">
		<div class="row top back-red f18">
			<div class="col-xs-1 col-md-1 white">
			<a class="clearfix" href="javascript:history.go(-1);">
			    <div class="glyphicon glyphicon glyphicon glyphicon-menu-left f18" aria-hidden="true"></div>
		    </a>
			</div>
			<div class="col-xs-10 col-md-10 white">我的代理</div>
			<div class="col-xs-1 col-md-1 white"></div>
		</div>
	</div>
	<div class="container mar-t">
		<div class="row back-white">
			<a class="clearfix" href="<?php echo Url::toRoute(['agency-detail','uid'=>$info->uid,'level'=>2]); ?>">
			<div class="panel-body bor-t1 bor-b1 gray">
			    <div class="pull-left blue"> 一级代理 </div>
			    <div class="glyphicon glyphicon glyphicon glyphicon-menu-right pull-right f18 grayer" aria-hidden="true"></div>
			    <div class="grayer pull-right f16 pa-r"><?php echo $first; ?></div>
		    </div>
		    </a>
	    </div>
		<div class="row back-white">
			<a class="clearfix" href="<?php echo Url::toRoute(['agency-detail','uid'=>$info->uid,'level'=>3]); ?>">
			<div class="panel-body bor-t1 bor-b1 gray mar--t">
			    <div class="pull-left orange"> 二级代理 </div>
			    <div class="glyphicon glyphicon glyphicon glyphicon-menu-right pull-right f18 grayer" aria-hidden="true"></div>
			    <div class="grayer pull-right f16 pa-r"><?php echo $second; ?></div>
		    </div>
		    </a>
	    </div>
    </div>

</div>

</body>
</html>