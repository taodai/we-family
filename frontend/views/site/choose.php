<?php
use yii\helpers\Html;
use yii\helpers\Url;
// use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '选择会员类型';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
<br>
	<div class="media">
      <div class="media-left">
        <a href="#">
          <img class="media-object" alt="140x140" src="/image/personal.jpg" data-holder-rendered="true" width="140" height="140">
        </a>
      </div>
      <div class="media-body">
        <h3 class="media-heading text-primary" id="middle-aligned-media">年费：12元/年</h3>
        <p>
	        1. 会员专属育儿资讯<br>
	        2. 各类幼儿专属音乐<br>
	        3. 亲子绘本课程<br>
	        4. 家用亲子游戏详案
    	</p>
      </div>
    </div>
    <br>
	    <p><button type="button" class="btn btn-primary btn-block" id="single">个人会员注册</button></p>
    <br>
	<div class="media">
      <div class="media-left">
        <a href="#">
          <img class="media-object" alt="140x140" src="../image/garden.jpg" data-holder-rendered="true" width="140" height="140">
        </a>
      </div>
      <div class="media-body">
        <h3 class="media-heading text-primary" id="middle-aligned-media">年费：980元/年</h3>
        <p>
	        1. 园所授牌<br>
	        2. 园长证书<br>
	        3. 专家研讨会<br>
	        4. 每月最新国内外教育资讯（园长）<br>
	        5. 每月最新国内外教育资讯（家长）<br>
	        6. 每月大型亲子活动详案<br>
	        7. 园所结对<br>
	        8.全年专家咨询服务
    	</p>
      </div>
      <br>
	    <p><button type="button" class="btn btn-primary btn-block" id="com">园所会员注册</button></p>
    </div>
</div>
<script type="text/javascript">
  $('#single').click(function(){
    window.location.href="<?php echo Url::toRoute(['site/choose','type'=>1])?>";
  });
  $('#com').click(function(){
    window.location.href="<?php echo Url::toRoute(['site/choose','type'=>2])?>";
  });
</script>

</body>
</html>