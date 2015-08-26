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
			<div class="col-xs-10 col-md-10 white">我的代理</div>
			<div class="col-xs-1 col-md-1 white"></div>
		</div>
	</div>
	<?php $data=$result->data;if(!empty($data)){ ?>
	<?php foreach($data as $key){ ?>
	<div class="container back-white bor-t1 bor-b1 mar-b">
		<div class="media pa-t pa-b">
		      <div class="media-left">
		        <a href="javascript:void(0);">
		          <img class="media-object bor1" alt="" src="<?php echo $key->userinfo->picUrl?$key->userinfo->picUrl:Url::base()."/image/mind-detail.jpg"; ?>" width="64" height="64" />
		        </a>
		      </div>
		      <div class="media-body media-t">
		        <h3 class="media-heading gray"><?php echo $key->userinfo->babyName; ?></h3>
		        <div class="clearfix gray">
		        	<div class="pull-left">推荐人：
		        	<?php if($level==2){
		        			$info=AppUserinfo::getByUid($key->userid);
		        			
		        		}else if($level==3){
		        			$info=AppUserinfo::getthreelevelname($key->child_id);
		        		};echo $info->babyName; ?>
		        	</div>
		        	<div class="pull-right">贡献收益：￥<?php echo $key->income; ?></div>
		        </div>
		      </div>
		    </div>
	    </div>

   	<?php }} ?>

    </div>
	
    

</body>
</html>