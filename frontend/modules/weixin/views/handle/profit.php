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
	<div class="container back-white">
        <!--<canvas id="canvas_circle">
            浏览器不支持canvas
        </canvas>-->
		<div id="container"></div>
	</div>


	<div class="container navbar-fixed-bottom back-white tx-c pa0 bor-t1 pa-t pa-b">
		<div class="col-xs-6 col-md-6 blue bor-r1 ">
			<div class="f16">一级收益</div>
			<div class="f24">￥<?php echo $leveltwo ?></div>
		</div>
		<div class="col-xs-6 col-md-6 orange">
			<div class="f16">二级收益</div>
			<div class="f24">￥<?php echo $levelthree ?></div>
		</div>
    </div>

</div>

</body>
	<script src="<?php echo Url::base(); ?>/js/highcharts.js"></script>
	<script src="<?php echo Url::base(); ?>/js/modules/exporting.js"></script>
	<script type="text/javascript" src="<?php echo Url::base(); ?>/js/themes/grid.js"></script>
<script>
	var height=window.screen.height;
var y=height/10;
y=parseInt(y);
$("#container").css("padding-top",y+"px");
$(function() {
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    });

    // Build the chart
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: '代理收益详情'
        },
        tooltip: {
            enabled :false,
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    },
                    connectorColor: 'silver'
                }
            }
        },
        series: [{
            name: "Brands",
            colorByPoint: true,
            data: [
                {name: "二级收益", y: <?php echo $twoper ?>},
                {
                    name: "一级收益",
                    y: <?php echo $oneper ?>,
                    sliced: true,
                    selected: false
                }
            ]
        }],
        credits:{
            enabled :false,
        }
    });
        
});
</script>
</html>