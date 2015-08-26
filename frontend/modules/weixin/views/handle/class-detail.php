<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use common\library\jssdk;
/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();
//print_r($signPackage);exit;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/css/style-hql.css">
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container-fluid back-white">
	<div class="media back-white pa-1 mar0">
		<div class="ub ub-ac">
			<div class="media-left pa0 ub ub-ver">
				<img class="media-object img-rounded bor1" alt="" src="<?php echo $user->info->picUrl?$user->info->picUrl:Url::base()."/image/mind-detail.jpg";  ?>" width="100" data-holder-rendered="true"/>
			</div>
			<div class="ub ub-ac ub-f1">
				<div class="pa-l">
			        <h4 class="media-heading blue">我是<strong class="blue"><?php echo $user->info->babyName; ?></strong></h4>
			        <p class="media-heading f16 gray">在哈奇乐早教学院听了大咖讲的课，太精彩了，特此推荐给你！</p>
		        </div>
			</div>
	    </div>
    </div>
<!--
	<div class="media row want-promote mar0 clearfix pa-b pa-t">
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
	        <img class="media-object img-thumbnail" data-src="" alt="" src="<?php echo $user->info->picUrl?$user->info->picUrl:Url::base()."/image/mind-detail.jpg";  ?>" width="100%" data-holder-rendered="true">
        </div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
    </div>
	<div class="media row want-promote mar0 clearfix pa-b">
		<div class="tx-c">
	        <h2 class="media-heading blue">我是 <strong class="blue"><?php echo $user->info->babyName; ?></strong></h2>
	        <p class="media-heading f24 gray">在哈奇乐早教学院听了大咖讲的课，太精彩了，特此推荐给你！</p>
		</div>
    </div>
-->
    <div><img src="<?php echo Url::base(); ?>/image/cla-det-1.jpg" width="100%" /></div>
	<div class="media row want-promote mar0 clearfix">
		<div class="tx-c white back-bluer pa-1">
			<br>
	        <h3 class="media-heading"><?php echo $class->theme ?></h3>
	        <p class="media-heading f20">开课时间 <strong><?php echo date("Y/m/d H:i",$class->share_time) ?></strong></p>
			<br>
		</div>
    </div>
    <div><img src="<?php echo $class->lecturer->class_pic?$class->lecturer->class_pic:Url::base()."/image/be-continued.jpg"; ?>" width="100%" /></div>
	<?php if($class->share_content!=""){ ?>
	<div class="pa-t pa-r pa-l f18">
		<h3 class="tx-l blue mar-t0">课程简介</h3>
		<p class="f16"><?php echo $class->share_content; ?></p>
	</div>
	<?php } ?>
    <div><img src="<?php echo Url::base(); ?>/image/scan.jpg" width="100%" /></div>
	<div class="row pa-1 mar0 clearfix back-white">
		<div class="col-xs-2 col-sm-2 col-md-2"></div>
		<div class="col-xs-8 col-sm-8 col-md-8"><img src="<?php echo $user->info->qrimg_url; ?>" width="100%"></div>
		<div class="col-xs-2 col-sm-2 col-md-2"></div>
	</div>
    <div><img src="<?php echo Url::base(); ?>/image/cla-det-2.jpg" width="100%" /></div>

</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
	wx.config({
		debug: false,
		appId: "<?=$signPackage['appId']?>",
		timestamp: <?=$signPackage['timestamp']?>,
		nonceStr: "<?=$signPackage['nonceStr']?>",
		signature: "<?=$signPackage['signature']?>",
		jsApiList: [
			'checkJsApi',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
		  ]
	});
	wx.ready(function () {
		var shareData = {
			title: '哈奇乐早教学院学员招募',
			desc: '我是<?= $user->info->babyName; ?>在哈奇乐早教学院听了大咖讲的课，太精彩了，特此推荐给你！',
			link: 'http://www.haqile.net/weixin/handle/class-detail?id=<?= $id ?>&openid=<?= $openid ?>',
			imgUrl: 'http://www.haqile.net/zaojiaologo.jpg'
		};
		wx.onMenuShareAppMessage(shareData);
		wx.onMenuShareTimeline(shareData);
	});
	wx.error(function (res) {
	  //alert(res.errMsg);
	});
	
</script>
</body>
</html>