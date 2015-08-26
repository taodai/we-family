<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php 
	$session = Yii::$app->session;
    $session->open();
    $checkcode=rand(100000,999999);
    $session['checkcode']=$checkcode;
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
		<div><img src="<?php echo Url::base(); ?>/image/advertising-1.jpg" width="100%" /></div>
		<div><img src="<?php echo Url::base(); ?>/image/advertising-2.jpg" width="100%" /></div>
		<div class="back-cyan pa-l pa-r">
			<br>
			<h2 class="tx-c pa-t white mar-t0">历史课程</h2>
			<ul class="list-group mem pa-t bor0 pa-b mar-b0">
				<li class="list-group-item">
					<a href="http://mp.weixin.qq.com/s?__biz=MzA4MDY4ODAyNA==&mid=205377451&idx=1&sn=fc5acb2624a81d1892d169e0faf821b9&scene=18&ptlang=2052&ADUIN=158931954&ADSESSION=1436874152&ADTAG=CLIENT.QQ.5419_.0&ADPUBNO=26486#rd">
					<div class="row white mar0">
						<div class="col-xs-8 col-md-8 f16 pa0 over">幼小衔接，共祝成长</div>
						<div class="col-xs-4 col-md-4 f16 pa0 tx-r over">讲师：周莹</div>
					</div>
					</a>
				</li>
				<li class="list-group-item">
					<a href="http://mp.weixin.qq.com/s?__biz=MzA4MDY4ODAyNA==&mid=205505686&idx=1&sn=53f5004f34f89abf3b1ce929686eeaf0&ptlang=2052&ADUIN=158931954&ADSESSION=1436874152&ADTAG=CLIENT.QQ.5419_.0&ADPUBNO=26486#rd">
					<div class="row white mar0">
						<div class="col-xs-8 col-md-8 f16 pa0 over">让孩子变好比让孩子变聪明更重要——品德教育奠定孩子的一生</div>
						<div class="col-xs-4 col-md-4 f16 pa0 tx-r over">讲师：Tina</div>
					</div>
					</a>
				</li>
				<li class="list-group-item">
					<a href="http://mp.weixin.qq.com/s?__biz=MzA4MDY4ODAyNA==&mid=205660042&idx=4&sn=e4d81476cab49d7265e8e39fb748603b&ptlang=2052&ADUIN=158931954&ADSESSION=1436874152&ADTAG=CLIENT.QQ.5419_.0&ADPUBNO=26486#rd">
					<div class="row white mar0">
						<div class="col-xs-8 col-md-8 f16 pa0 over">父母如何为宝宝选择幼儿园</div>
						<div class="col-xs-4 col-md-4 f16 pa0 tx-r over">讲师：郑涵</div>
					</div>
					</a>
				</li>
				<li class="list-group-item">
					<a href="http://mp.weixin.qq.com/s?__biz=MzA4MDY4ODAyNA==&mid=205784415&idx=1&sn=40defb9ff3ccd0c7774895ea34a8d1d2&ptlang=2052&ADUIN=158931954&ADSESSION=1436874152&ADTAG=CLIENT.QQ.5419_.0&ADPUBNO=26486#rd">
					<div class="row white mar0">
						<div class="col-xs-8 col-md-8 f16 pa0 over">快乐入托，爸妈放心——宝宝入托指导攻略</div>
						<div class="col-xs-4 col-md-4 f16 pa0 tx-r over">讲师：周莹</div>
					</div>
					</a>
				</li>
				<li class="list-group-item">
					<a href="http://mp.weixin.qq.com/s?__biz=MzA4MDY4ODAyNA==&mid=205919566&idx=1&sn=360bd636afc545813a4915ec0e6a9fa4&ptlang=2052&ADUIN=158931954&ADSESSION=1436874152&ADTAG=CLIENT.QQ.5419_.0&ADPUBNO=26486#rd">
					<div class="row white mar0">
						<div class="col-xs-8 col-md-8 f16 pa0 over">握先机，趁早帮助孩子——浅谈0-3岁教育的意义</div>
						<div class="col-xs-4 col-md-4 f16 pa0 tx-r over">讲师：Tina</div>
					</div>
					</a>
				</li>
			</ul>
			<br>
		</div>
		<div><img src="<?php echo Url::base(); ?>/image/advertising-3.jpg" width="100%" /></div>
		<div class="row team mar0 clearfix">
			<?php if(!empty($lec)){ ?>
				<?php foreach($lec as $key){ ?>
					<div class="col-xs-4 col-sm-4 col-md-4">
						<a href="<?php echo Url::toRoute(['lecturer','id'=>$key->leid]); ?>">
							<img src="<?php echo $key->reg_pic; ?>" width="100%" />
						</a>
					</div>
				<?php } ?>
			<?php } ?>
      	</div>
		<div><img src="<?php echo Url::base(); ?>/image/advertising-4.jpg" width="100%" /></div>
		<div><img src="<?php echo Url::base(); ?>/image/advertising-42.jpg" width="100%" /></div>
		<div><img src="<?php echo Url::base(); ?>/image/advertising-43.jpg" width="100%" /></div>
		<div><img src="<?php echo Url::base(); ?>/image/advertising-5.jpg" width="100%" /></div>
		
		<!-- 我要加入 -->
		<?php if($isreg==0){ ?>
		<div class="back-reder">
			<div><img src="<?php echo Url::base(); ?>/image/advertising-61.jpg" width="100%" /></div>
			<form class="form-horizontal login">
				<div class="form-group login clearfix">
					<label for="inputEmail3" class="col-xs-4 col-sm-4 col-md-4 control-label f18 tx-r white">手机号码</label>
					<div class="col-xs-4 col-sm-4 col-md-4">
						<input class="form-control" type="text" value="" id="mobile">
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4">
						<button type="button" class="btn btn-default" id="getCode">获取验证码</button>
					</div>
				</div>
				<br>
				<div class="form-group login clearfix">
					<label for="inputEmail3" class="col-xs-4 col-sm-4 col-md-4 control-label f18 tx-r white">验证码</label>
					<div class="col-xs-4 col-sm-4 col-md-4">
						<input class="form-control" type="text" value="" id="code">
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4"></div>
				</div>
				<br>
			</form>
			<div class="pay pa-ler pa-rer">
				<button type="button" class="btn btn-default btn-lg btn-block borra25 bor0 reder f24" id="pay">支付48元完成加入</button>
			</div>
			<br>
		</div>
		<?php }else if($isreg==1){ ?>
		<!-- 已是会员 -->
		<div class="back-reder"><!-- sr-only是隐藏 -->
			<div><img src="<?php echo Url::base(); ?>/image/advertising-7.jpg" width="100%" /></div>
			<div class="row already mar0 clearfix back-white">
				<div class="col-xs-2 col-sm-2 col-md-2"></div>
				<div class="col-xs-4 col-sm-4 col-md-4">
					<a href="<?php echo Url::toRoute(['promote','uid'=>$user->uid]); ?>">
						<img src="<?php echo Url::base(); ?>/image/advertising-8.jpg" width="100%" />
					</a>
				</div>
				<div class="col-xs-4 col-sm-4 col-md-4">
					<a href="<?php echo Url::toRoute(['menbercenter','code'=>$code]); ?>">
						<img src="<?php echo Url::base(); ?>/image/advertising-9.jpg" width="100%" />
					</a>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-2"></div>
	      	</div>
		</div>
		<?php } ?>
	<input type="hidden" value="<?php echo $uid ?>" id="uid">
	<input type="hidden" value="<?php echo $openid ?>" id="useropenid">
	<input type="hidden" value="<?php echo $nickname ?>" id="nickname">
	<input type="hidden" value="<?php echo $headimgurl ?>" id="headimgurl">
	<input type="hidden" value="<?php echo $money ?>" id="money">
	<input type="hidden" value="<?php echo $sex ?>" id="sex">
	<input type="hidden" value="<?php echo $checkcode ?>" id="checkcode">
	<input type="hidden" value="<?php echo $orderid ?>" id="orderid">
</div>
<script>
    var timeout;
    var count = 60; // 倒数
    var paycount = 1000;
    var is_sub = 0;
    $(function() {
        $('#getCode').on('click',function(){
            if($("#mobile").val()==""){
                alert("请输入手机号码");
                return false;
            }else{
                setTimeout(BtnCount, 1000);
                getMessage();
            }
        });
        $('#pay').on('click',function(){
            setTimeout(PayCount, 1);
            pay();
            
        });
    });
    BtnCount = function() {
           // 启动按钮
        if (count == 0) {
            count = 60;
            $("#getCode").addClass("t-gra3").removeClass('t-wh3').text("获取验证码").removeAttr("disabled");
        }else{
            count--;
            $("#getCode").attr("disabled", true);
            $("#getCode").addClass("t-wh3").removeClass('t-gra3').text("请等待"+count + "秒");
            setTimeout(BtnCount, 1000);
        }
    };
    PayCount = function() {
           // 启动按钮
        if (paycount == 0) {
            paycount = 1000;
            $("#pay").removeAttr("disabled");
        }else{
            paycount--;
            $("#pay").attr("disabled", true);
            setTimeout(PayCount, 1);
        }
    };
function getMessage(){
    $.ajax({
        type:"POST",
        url:'http://api.haqile.net/smsApi/sms/sms-send-no',
        data:{"uname":$("#mobile").val()},
        dataType:'json',
        async:false,
        error:function(XMLResponse){
            // alert(XMLResponse.responseText);
            // return false;
        },
        success:function(data){
            if(data){
            	if(data.code==1){
            		alert("获取验证码成功，请注意查询短信");
            	}else{
            		alert(data.msg);
            	}
                
            }else{
                alert("验证码获取失败，请重新尝试");
            }  
        }
    });
}
function pay(){

	if($("#mobile").val()==""){
        alert("请输入手机号码");
        return false;
    }
    if($("#code").val()==""){
        alert("请输入验证码");
        return false;
    }
    if(is_sub==0){
    	$.ajax({
	        type:"POST",
	        url:'http://api.haqile.net/memberApi/member/check-code',
	        data:{"uname":$("#mobile").val(),'code':$("#code").val()},
	        dataType:'json',
	        async:false,
	        error:function(XMLResponse){
	            // alert(XMLResponse.responseText);
	            // return false;
	        },
	        success:function(data){
	            if(data){
	            	if(data.code==1){
	            		
	            		if(is_sub==0){
	            			is_sub=1;
	            			callpay();
	            			//location.href='/weixin/handle/reg-succeed?uname='+$("#mobile").val()+"&uid="+$("#uid").val()+"&useropenid="+$("#useropenid").val()+"&nickname="+$("#nickname").val()+"&headimgurl="+$("#headimgurl").val()+"&paymoney="+$("#money").val()+"&sex="+$("#sex").val()+"&checkcode="+$("#checkcode").val();
    						return false;
    					}
	            	}else{
	            		alert(data.msg);
	            	}
	                
	            }else{
	                alert("请重新尝试");
	            }  
	        }
	    });
    }
    

}
//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg=="get_brand_wcpay_request:ok"){
						location.href='/weixin/handle/reg-succeed?uname='+$("#mobile").val()+"&uid="+$("#uid").val()+"&useropenid="+$("#useropenid").val()+"&nickname="+$("#nickname").val()+"&headimgurl="+$("#headimgurl").val()+"&paymoney="+$("#money").val()+"&sex="+$("#sex").val()+"&checkcode="+$("#checkcode").val()+"&orderid="+$("#orderid").val();
					}
					
					//alert(res.err_code+"--"+res.err_desc+"--"+res.err_msg);
				}
			);
		}

		function callpay()
		{
			
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
		function getRandom(n){
        return Math.floor(Math.random()*n+1)
        }
</script>

</body>
</html>