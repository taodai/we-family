<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
// use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '会员注册';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile($this->registerJsFile("@web/js/reg.js",['position'=>View::POS_END]));
?>
<div class="container">
<br>
<form class="form-horizontal" action="<?php echo Url::toRoute('/site/signup')?>" method="post" id="form">
    <div class="form-group form-group-lg">
        <label for="phone" class="col-sm-2 control-label"> 手机号码 </label>
        <div class="col-sm-10">
            <input type="tel" class="form-control" datatype="m" errormsg="手机号码格式不正确" id="phone" name="uname" 
            ajaxurl="<?php echo Url::toRoute(['site/check'])?>">
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label for="code" class="col-sm-2 control-label show"> 验证码 </label>
        <div class="col-sm-4 col-xs-12">
            <div class="input-group">
                <input type="text" class="form-control" id="code" name="code" datatype="*6-6" errormsg="验证码输入不正确！">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-lg" type="button" id="codesend">发送验证码</button>
                </span>
            </div>
            <!-- <span id="helpBlock" class="help-block" style="display:none;">验证码输入错误</span> -->
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label for="inputPassword" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-10">
            <input type="password" name="password"  class="form-control" datatype="*6-12" errormsg="密码范围在6~12位之间！" id="inputPassword" >
        </div>
    </div>


    <div class="form-group form-group-lg">
        <label for="inputPassword2" class="col-sm-2 control-label">确认密码</label>
        <div class="col-sm-10">
            <input type="password" name="repassword" class="form-control" datatype="*" recheck="password" errormsg="两次输入的密码不一致" id="inputPassword2">
        </div>
    </div>
    <br>
    <p><button type="submit" class="btn btn-primary btn-block btn-lg">提交</button></p>
</form>
</div>
<script type="text/javascript">
  $('#codesend').click(function(){
    var mobile = $('#phone').val();
    var className = 'has-error';
    var bool = true;
    if(mobile=='')
    {
      if($('#phone').next('.help-block').length == 0){
        $('#phone').after('<span class="help-block">请先输入手机号码</span>');
      }else{
        $('#phone').next('.help-block').text('请先输入手机号码');
      }
      bool = false;
    }else{
      if($('#phone').next('.help-block').length > 0 ){
        bool = false;
      }
    }
    $('#phone').parents('.form-group').removeClass('has-error');
    $('#phone').parents('.form-group').addClass(className);
    if(bool){
      $.ajax({ 
        type: "POST", 
        url: "<?php echo Url::toRoute('site/sms-send');?>", 
        cache: false, 
        async: false, 
        data:{mobile:mobile},
        dataType: "json", 
        success: function(data) {

        }
      });
    }
  });
    // $.ajax({ 
    //   type: "POST", 
    //   url: "<?php echo Url::toRoute('site/menu');?>", 
    //   cache: false, 
    //   async: false, 
    //   data:{mobile:mobile},
    //   dataType: "json", 
    //   success: function(data) {

    //   }
  $('#form').Validform({
          ajaxPost: true,
          tiptype: function (msg, o, cssctl) {
              if (!o.obj.is("form")) {
                  //设置提示信息
                  var objtip = o.obj.siblings(".help-block");
                  var objtiptwo = o.obj.parent().siblings(".help-block");
                  if (o.type == 2) {
                      //通过
                      var className = 'has-success';
                      if(objtip){
                        objtip.remove();
                      }
                      if(objtiptwo){
                        objtiptwo.remove();
                      }
                  }
                  if (o.type == 3) {
                      var className = 'has-error';
                      if ( o.obj.next('.help-block').length == 0) {
                          if(o.obj.attr('id') == 'code'){
                            objtiptwo.remove();
                            o.obj.parent().after('<span class="help-block">' + msg +'</span>');
                          }else{
                            o.obj.after('<span class="help-block">' + msg +'</span>');
                          }
                      }else{
                          o.obj.next('.help-block').text(msg);
                      }
                  }
                  //设置样式
                  o.obj.parents('.form-group').removeClass('has-error');
                  o.obj.parents('.form-group').addClass(className);
              }
          },
          beforeSubmit:function(curform){
              
              //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
              //这里明确return false的话表单将不会提交;  
          },
          callback:function(data){
            alert(data.info);
            // $.Showmsg(data.info);
            // if(data.status=='y'){
            //   alert(data.info);
            // }
            //返回数据data是json对象，{"info":"demo info","status":"y"}
            //info: 输出提示信息;
            //status: 返回提交数据的状态,是否提交成功。如可以用"y"表示提交成功，"n"表示提交失败，在ajax_post.php文件返回数据里自定字符，主要用在callback函数里根据该值执行相应的回调操作;
            //你也可以在ajax_post.php文件返回更多信息在这里获取，进行相应操作；
            //ajax遇到服务端错误时也会执行回调，这时的data是{ status:**, statusText:**, readyState:**, responseText:** }；
          
            //这里执行回调操作;
            //注意：如果不是ajax方式提交表单，传入callback，这时data参数是当前表单对象，回调函数会在表单验证全部通过后执行，然后判断是否提交表单，如果callback里明确return false，则表单不会提交，如果return true或没有return，则会提交表单。
          }
      });
</script>

