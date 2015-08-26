<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
$this->registerJsFile("@web/theme/easyui131/jquery.edatagrid.js",['position'=>View::POS_END]); 
$this->registerCssFile("@web/theme/system_admin/ui_login.css",['position'=>View::POS_END]); 
?>
<div id="login_admin">
<div class="login_admin_inside">
<form id="loginForm" name="loginForm" action="" method="post">
    
<p>用户名:<input type="text" name="login_name"  id="username" class="easyui-validatebox input_text" required="true" validType="length[1,20]"/></p>
<p class="input_pwd">密 码:<input type="password" name="password"  id="password" class="easyui-validatebox input_text" required="true" validType="length[4,20]"/></p>


<!-- <p>验证码:
    <input type="captcha" id="captcha" name="captcha" class="easyui-validatebox input_text" required="true"
    validType="length[1,4]" style="margin-left: 0px;" autocomplete="off"/>

</p>

<p>验证图: 
    <img id="code_show_img" src="<?php //echo site_url("system_admin/login/captcha"); ?>?" style="width:100px;height:20px;" />
    <a href="#" class="change_captcha" onClick="$('#code_show_img').attr('src',$('#code_show_img').attr('src')+'1');"  >换一个</a>
</p> -->

<p class="save_info">
<a href="#" class="login_login" id="login_login">登录</a>
<a href="javascript:butun_reset();" id="butun_reset">重置</a>
</p>
 
</form>  
</div>
<div id="login_bottom">
</div>
</div>
<script type="text/javascript" >
$(document).ready(function(){
    $("#login_login").click(function(){ 
        var url = "<?php echo Url::to('site/login')?>";
        $('#loginForm').form('submit',{  
            url: url,  
            onSubmit: function(){  
                return $(this).form('validate');  
            },  
            success: function(result2){
                var result = eval('('+result2+')'); 
                if (result.errorMsg){  
                    // $('#username').val('');
                    // $('#password').val('');
                    // $('#captcha').val('');
                    // $('#code_show_img').attr('src',$('#code_show_img').attr('src')+'1');
                    $.messager.show({  
                        title: '错误提示',  
                        msg: result.errorMsg
                    });  
                } else {  
                    window.location.href = "<?php echo Url::to('/site/main')?>";
                }  
            }  
        });
    }); 
});

function butun_reset(){
    $('#username').val('');
    $('#password').val('');
    // $('#captcha').val('');  
}
function BindEnter(obj)
{
    //使用document.getElementById获取到按钮对象
    var button = document.getElementById('login_login');
    if(obj.keyCode == 13)
    {
         button.click();
         obj.returnValue = false;
     }
}
</script>