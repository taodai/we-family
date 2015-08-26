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

<div class="container-fluid">
	<div><img src="<?php echo Url::base(); ?>/image/class-1.jpg" width="100%"></div>
	<div><img src="<?php echo Url::base(); ?>/image/class-2.jpg" width="100%"></div>
	<div><h3 class="tx-c back-gray gray mar0 title">课程预告</h3></div>
	<div>
		<ul class="list-group class-list bor0 pa-l pa-r mar-b0 back-white">
			<?php if(!empty($class)){ ?>
				<?php foreach($class as $key){ ?>
					<li class="list-group-item">
						<?php if($isreg==0){ ?>
						<a class="over" onclick="alert('请先注册成为学员')" href="<?php echo Url::toRoute(['weixin-reg','code'=>$code]); ?>"><?php echo $key->theme; ?></a>
						<?php }else if($isreg==1){ ?>
						<a class="over" href="<?php echo Url::toRoute(['class-detail','id'=>$key->cnid,'openid'=>$openid]); ?>"><?php echo $key->theme; ?></a>
						<?php } ?>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>

</div>

</body>
</html>