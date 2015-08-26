<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

use common\library\WeixinApi;
/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php 
$wxapi=new WeixinApi();
$url='http://api.haqile.net/agencyApi/agency/get-income';
$par['uid']=$user->uid;
$result=$wxapi->http_post($url,$par);
$income=json_decode($result);
//print_r($income);
if(empty($income->data)){
	$incomefee="0.00";
	$cash_left="0.00";
}else{
	$incomefee=$income->data->income_total;
	$cash_left=$income->data->cash_left;
}

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


<script type="text/javascript" src="<?php echo Url::base(); ?>/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
	<div class="row top back-red f18 mar0">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 white">会员中心</div>
	</div>
	<div class="media back-white pa-t pa-r pa-b pa-l mar0">
		<div class="media-left">
			<img class="media-object img-rounded bor1" alt="个人会员" src="<?php echo $info->picUrl?$info->picUrl:Url::base()."/image/mind-detail.jpg"; ?>" data-holder-rendered="true" width="100" height="100" />
		</div>
		<div class="media-body media-t">
			<h3 class="media-heading text-primary" id="middle-aligned-media">你好<?php echo $info->babyName ?></h3>
			<p class="lh16 f16">
				学号：<?php echo $user->studyid ?><br>
				班长微信：eceilele<br>
			</p>
		</div>
    </div>
	<div class="row text-center mar0">
		<div class="col-xs-6 col-md-6 back-ye ear-pad-tb gray" >
      		<div class="f16">我的收益</div>
      		<div class="f24"><strong>￥<?php echo $incomefee; ?></strong></div>
		</div>
		<div class="col-xs-6 col-md-6 back-blue ear-pad-tb gray">
      		<div class="f16">可提现金额</div>
      		<div class="f24"><strong>￥<?php echo $cash_left; ?></strong></div>
		</div>
	</div>
	<div class="row mar0 back-white">
		<!--<div class="col-xs-12 col-md-12 panel-body bor1 gray" id="goto" onclick="newOpen('agency.html')">-->
		<a class="clearfix" href="<?php echo Url::toRoute(['agency','uid'=>$user->uid]); ?>">
		<div class="panel-body bor-t1 bor-b1 gray">
		    <div class="pull-left"> 我的代理 </div>
		    <div class="glyphicon glyphicon glyphicon glyphicon-menu-right pull-right f18 grayer" aria-hidden="true"></div>
	    </div>
	    </a>
    </div>
	<div class="row mar0 back-white">
		<a class="clearfix" href="<?php echo Url::toRoute(['profit','uid'=>$user->uid]); ?>">
		<div class="panel-body bor-t1 bor-b1 gray mar--t">
		    <div class="pull-left"> 我的收益 </div>
		    <div class="glyphicon glyphicon glyphicon glyphicon-menu-right pull-right f18 grayer" aria-hidden="true"></div>
	    </div>
	    </a>
    </div>
	<div class="row mar0 back-white">
		<a class="clearfix" href="<?php echo Url::toRoute(['profit-detail','uid'=>$user->uid]); ?>">
		<div class="panel-body bor-t1 bor-b1 gray mar--t">
		    <div class="pull-left"> 财务明细 </div>
		    <div class="glyphicon glyphicon glyphicon glyphicon-menu-right pull-right f18 grayer" aria-hidden="true"></div>
	    </div>
	    </a>
    </div>
	<div class="row mar0 back-white">
		<a class="clearfix" href="javascript:void(0);" onclick="alert('该功能正在开发中');">
		<div class="panel-body bor-t1 bor-b1 gray mar--t">
		    <div class="pull-left"> 我要提现 </div>
		    <div class="glyphicon glyphicon glyphicon glyphicon-menu-right pull-right f18 grayer" aria-hidden="true"></div>
	    </div>
	    </a>
    </div>
	<div class="row mar0" >
		<div class="panel-body">
			<a href="<?php echo Url::toRoute(['promote','uid'=>$user->uid]); ?>"><div class="all-btn text-center back-red borra white f16" >分享我的专属推广码到微信朋友圈</div></a>
	    </div>

    </div>

</div>

<!--<script type="text/javascript" src="<?php echo Url::base(); ?>/js/weixin-jssdk.js"></script>-->
</body>
</html>