<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use common\models\AppUserinfo;
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
			<div class="col-xs-10 col-md-10 white">我的收益</div>
			<div class="col-xs-1 col-md-1 white"></div>
		</div>
	</div>
	<div class="container back-white pa0">
		<div class="bs-example-tabs back-gray" data-example-id="togglable-tabs">
		    <ul id="myTabs" class="nav nav-tabs row mar0 pay-details f16 back-white" role="tablist">
				<li role="presentation" class="col-xs-6 col-md-6 pa0 active"><a href="#earnings" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">收益</a></li>
				<li role="presentation" class="col-xs-6 col-md-6 pa0"><a href="#withdrawal" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">提现</a></li>
		    </ul>
		    <div id="myTabContent" class="tab-content mar-t back-white">

		    	<?php $data=$result->data;if(!empty($data)){ ?>
		    	
				<div role="tabpanel" class="bor-t1 tab-pane fade active in" id="earnings" aria-labelledby="home-tab">
					<ul class="pa0 mar0">
					<?php foreach($data as $key){ ?>
						<li class="row pa-t pa-b bor-b1 mar0">
							<div class="col-xs-5 col-md-5 gray">
							<?php echo $key->level==2?"一级代理：":"二级代理："; ?>
							<?php $info=AppUserinfo::getByUid($key->child_id);echo $info->babyName; ?>
							</div>
							<div class="col-xs-3 col-md-3 tx-c red"><?php echo $key->income ?></div>
							<div class="col-xs-4 col-md-4 tx-r grayer"><?php echo date("Y-m-d",$key->income_time); ?></div>
						</li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>


				<div role="tabpanel" class="bor-t1 tab-pane fade" id="withdrawal" aria-labelledby="profile-tab" style="display: none">
					<ul class="pa0 mar0">
						<li class="row pa-t pa-b bor-b1 mar0">
							<div class="col-xs-8 col-md-8 green">-40</div>
							<div class="col-xs-4 col-md-4 tx-r grayer">2015-06-05</div>
						</li>
						<li class="row pa-t pa-b bor-b1 mar0">
							<div class="col-xs-8 col-md-8 green">-40</div>
							<div class="col-xs-4 col-md-4 tx-r grayer">2015-06-05</div>
						</li>
						<li class="row pa-t pa-b bor-b1 mar0">
							<div class="col-xs-8 col-md-8 green">-40</div>
							<div class="col-xs-4 col-md-4 tx-r grayer">2015-06-05</div>
						</li>
					</ul>
				</div>
		    </div>
		</div>
	</div>

</div>

</body>
</html>