<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
$this->registerCssFile("@web/theme/default/default.css",['position'=>View::POS_END]); 
$this->registerCssFile("@web/theme/system/ui_main.css",['position'=>View::POS_END]); 
$this->registerCssFile("@web/theme/system/helper/style.css",['position'=>View::POS_END]); 
$this->registerJsFile("@web/theme/system/helper/widget.js",['position'=>View::POS_END]); 
?>       
<div region="north" id="top_region_north" split="true" style="height:40px; padding-left:20px;">
    <div style="float:left;font-size:18px;margin-top:8px;">点精教育管理平台</div>
    <div class="tabs-header tabs-header-noborder" style="float:left; padding-left:78px;">
        <ul class="tabs" style="margin:0; height:37px; border:0; width:auto;" id="module">
        <?php foreach($mod as $key=>$val): ?>
            <li class="tabs-selected"><a id="<?=$val['mo_tag']?>" href="#" class="tabs-inner top-menu"><span class="tabs-title"><?=$val['mo_title']?></span><span class="tabs-icon"></span></a></li>
        <?php endforeach ?>
        </ul>
    </div>
    <div style="float:right;">
        <div class="topmenubutton" style="background:url(/theme/easyui122/themes/icons/exit.png) no-repeat left center; margin-top:8px;">
        <a href="javascript:void(0);" onclick='exitsystem()'>退出系统</a>
        </div>
        <div class="topmenubutton" style="background:url(/theme/easyui122/themes/icons/key.png) no-repeat left center; margin-top:8px;">
        <a href="javascript:void(0);" onclick='showmodpassword()';>修改密码</a>
        </div>
<!--         <div class="topmenubutton" style="background:url(/theme/easyui122/themes/icons/comments.png) no-repeat left center; margin-top:8px;">
        <a href="<?php echo Url::toRoute('/gii')?>" target="blank">gii</a>
        </div> -->
    </div>
</div>
<div region="south" id="top_region_south" split="true" style="height:35px;padding:4px;">
    当前登录: <?php echo Yii::$app->session->get('manager')->realName; ?> &nbsp;&nbsp;&nbsp;<!-- 用户类型：<?php //echo $type;?> &nbsp;&nbsp;&nbsp; -->当前日期：<?php echo date('Y年m月d日');?> &nbsp;&nbsp;&nbsp;星期<?php echo mb_substr("日一二三四五六",date("w"),1, 'utf-8');?>
</div>
<div region="west" split="true" title="功能列表"  style="width:12%;padding:0px;overflow:hidden;">
    <div class="easyui-accordion" fit="true" border="false" id="west" data-options="href:'_content.html'">
        <div title="系统设置" iconCls="icon-customer"  class="t_center" style="padding-left:10px;">
            <p class="menubutton" style="background:url('/theme/easyui131/themes/icons/department.png') no-repeat left center;">
                <a href="javascript:void(0);" style="margin:10px;" onclick="addMaintab('后台模块管理','/system/module','');">后台模块管理</a>
            </p>
        </div>
    </div>
</div>        
<div region="center">
    <div id="maintab"  class="easyui-tabs"  fit="true" border="false">
       <div id='home' title="首页" iconCls="icon-home" style="padding:20px;overflow:hidden;"> 
       <!-- <div style="margin:20px 0 10px 0;"></div> -->
<!--        <div class="easyui-tabs" fit="true" id="maintab2">
        <div title="公告列表" style="padding:10px;">

            <div class="easyui-accordion"  style="width:50%;">
            </div>

        </div>
       </div> -->
       </div>
</div>
<div id="dlg" class="easyui-dialog singleColumn" 
    closed="true" style="width:400px;height:190px;">
<form id="loginForm" name="loginForm" action="" method="post" validate>
  <dl>
   <dt><label for="old_password">&nbsp;&nbsp;原 密 码  ：</label></dt>
    <dd><input class="easyui-validatebox input_text" type="password" name="old_password" required='true' size="20"/></dd>
   <dt><label for="mima">&nbsp;&nbsp;新 密 码  ：</label></dt>
   <dd><input class="easyui-validatebox input_text" type="password" name="new_password" id="password" validType="length[6,20]" required='true' size="20"/></dd>
   <dt><label for="mima">&nbsp;&nbsp;密码确认：</label></dt>
    <dd><input class="easyui-validatebox input_text" type="password" required='true' size="20" validType="equals['#password']"/></dd>
    <dt>　</dt>
    <dd><a class="easyui-linkbutton" iconCls="icon-ok" href="javascript:void(0)" onClick="editPassword()">确定</a>
        <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onClick="javascript:$('#dlg').dialog('close')">取消</a></dd>
  </dl>
</form>
</div> 
<div id="menu" class="easyui-menu" style="width:150px;">
    <div id="m-refresh">刷新</div>
    <div class="menu-sep"></div>
    <div id="m-closeall">全部关闭</div>
    <div id="m-closeother">除此之外全部关闭</div>
    <div class="menu-sep"></div>
    <div id="m-close">关闭</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#modpassword").window('close');
    $('#module').find('a').bind('click',function(){
        $(this).parent().siblings().find('a').removeClass('act');
        $(this).addClass('act');
        $.ajax({ 
          type: "POST", 
          url: "<?php echo Url::toRoute('site/menu');?>", 
          cache: false, 
          async: false, 
          data:{type:$(this).attr('id')},
          dataType: "json", 
          success: function(data) { 
                var p = $('#west').accordion('panels');
                $(p).each(function(){
                    var index = $('#west').accordion('getPanelIndex',$(this));
                    $("#west").accordion('remove',index);
                });
                for (var i=0;i<data.length;i++)
                {
                    var title = data[i].sm_menu_title;
                    var iconCls = data[i].sm_menu_icon;
                    var content = '';
                    if(data[i].children){
                        var child = data[i].children;
                        for(var j=0;j<child.length;j++){
                            var url = "<?php echo Url::base();?>"+"/"+child[j].sm_menu_url;
                            var str = "'"+child[j].sm_menu_title+"','"+url+"','"+child[j].sm_menu_icon+"','"+child[j].sm_id+"'";
                            content += '<p class="menubutton" style="background:url(/theme/easyui131/themes/icons/'+child[j].sm_menu_png+') no-repeat left center;"><a href="javascript:void(0);" style="margin:10px;" onclick="addMaintab('+str+');">'+child[j].sm_menu_title+'</a></p>';
                        }  
                        $('#west').accordion('add',{
                            title:title,
                            iconCls:iconCls,
                            content:content
                        });
                    }
                }
                $('#west').accordion().select(0);
            }  
        }); 
    });
    //刷新
    $("#m-refresh").click(function(){
        var currTab = $('#maintab').tabs('getSelected');    //获取选中的标签项
        var url = currTab.panel('options').url;    //获取该选项卡中内容标签（iframe）的 src 属性
        /* 重新设置该标签 */
        if(url==undefined){
            window.location.reload();
        }else{
            $('#maintab').tabs('update',{
                tab:currTab,
                options:{
                    content: createTabContent(url)
                }
            })
        }
    });
    
    //关闭所有
    $("#m-closeall").click(function(){
        $(".tabs li").each(function(i, n){
            var title = $(n).text();
            if(title!='首页'){
                $('#maintab').tabs('close',title);    
            }
        });
    });
    
    //除当前之外关闭所有
    $("#m-closeother").click(function(){
        var currTab = $('#maintab').tabs('getSelected');
        currTitle = currTab.panel('options').title;    

        $(".tabs li").each(function(i, n){
            var title = $(n).text();
            
            if(currTitle != title && title!='首页'){
                $('#maintab').tabs('close',title);            
            }
        });
    });
    
    //关闭当前
    $("#m-close").click(function(){
        var currTab = $('#maintab').tabs('getSelected');
        currTitle = currTab.panel('options').title;    
        $('#maintab').tabs('close', currTitle);
    });

     /*为选项卡绑定右键*/
    $(".tabs li").live('contextmenu',function(e){
        /* 选中当前触发事件的选项卡 */
        var subtitle =$(this).text();
        if(subtitle != '公告列表'){
            $('#maintab').tabs('select',subtitle);
            
            //显示快捷菜单
            $('#menu').menu('show', {
                left: e.pageX,
                top: e.pageY
            });
        }
        
        return false;
    });
});

function createTabContent(url){
    return '<iframe style="width:100%;height:100%;" scrolling="auto" frameborder="0" src="' + url + '"></iframe>';
}

function addMaintab(title,URL,icon,id){
    if ($('#maintab').tabs('exists', title)){
            $('#maintab').tabs('select', title);
        } else {
            var content = '<iframe scrolling="auto" frameborder="0" name="'+icon+'-'+id+'"  src="'+URL+'" style="width:100%;height:100%;"></iframe>';
            $('#maintab').tabs('add',{
                title:title,
                content:content,
                iconCls:icon,
                closable:true,
                url:URL
            });
        }
}

function exitsystem(){
    $.messager.confirm('提示', '确定要退出系统吗？', function(r){  
        if (r){  
            window.location.href="<?php echo Url::toRoute('/site/logout')?>";
        }  
    }); 
}
function showmodpassword(){
  $('#dlg').dialog('open').dialog('setTitle','修改密码');
  resetForm('loginForm');
  // $('#loginForm').validatebox('remove'); 
}
$.extend($.fn.validatebox.defaults.rules, {  
equals: {
    validator: function(value,param){
        return value == $(param[0]).val();  
    },  
        message: '密码验证不一致'  
    }  
});

function editPassword(){
    $("#loginForm").form('submit',{ 
        url: "<?php echo Url::toRoute('/site/logout')?>",  
        onSubmit: function(){  
            return $(this).form('validate');
        },  
        success: function(result){
            result = eval("(" + result + ")");;
            if (result.success == true) {
                $.messager.alert("提示", "修改成功");
                $('#dlg').dialog('close'); 
            }  else {
                $.messager.alert("提示", result.errorMsg);
            }
        }  
    }); 
}
</script>    