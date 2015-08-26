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

<div class="container-fluid">
	<div><img src="<?php echo Url::base(); ?>/image/promote-1.jpg" width="100%"></div>
	<div class="media row promote mar0 clearfix pa-b">
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
	        <img class="media-object img-thumbnail" data-src="" alt="" src="<?php echo $info->picUrl?$info->picUrl:Url::base()."/image/mind-detail.jpg"; ?>" width="100%" data-holder-rendered="true">
        </div>
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
    </div>
	<div class="media row promote mar0 clearfix">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tx-c">
	        <h2 class="media-heading white">我是 <strong class="white"><?php echo $info->babyName ?></strong></h2>
	        <h2 class="media-heading white">我为哈奇乐代言</h1>
		</div>
    </div>
	<div><img src="<?php echo Url::base(); ?>/image/promote-2.jpg" width="100%"></div>
	<div class="row pa-t mar0 clearfix back-reder">
		<div class="col-xs-3 col-sm-3 col-md-3"></div>
		<div class="col-xs-6 col-sm-6 col-md-6"><img src="<?php echo $info->qrimg_url; ?>" width="100%"></div>
		<div class="col-xs-3 col-sm-3 col-md-3"></div>
	</div>
	<div><img src="<?php echo Url::base(); ?>/image/promote-3.jpg" width="100%"></div>
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
			desc: '最有干货，最有内涵，覆盖面最广的在线早教学院，7月31日前加入免终身学费48元，再送8套家庭绘本故事资源包',
			link: 'http://www.haqile.net/weixin/handle/promote?uid='+<?php echo $uid ?>,
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