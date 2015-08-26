<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/easyui131/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/easyui131/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/system/helper/style.css">
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/bianjie_staly.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/widget.js"></script> 
</head>
<body>
    <table id="dg" pageSize="20" toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" fit="true">  
        <thead>  
            <tr>
                <th field="id" width="20" sortable="true">编号</th>
                <th field="appId" width="80">应用ID</th>
                <th field="appKey" width="80">应用密钥</th>
                <th field="appName" width="80">应用名称</th>
                <th field="appToken" width="80">应用token</th>
                <th field="p_status" width="50" formatter="format_status">状态</th>
                <th field="time_on" width="50" >开通时间</th>
                <th field="time_off" width="50" >停止时间</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="enable()" id="enable">启用</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="disable()" id="disable">停用</a>
    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" 
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="appName">应用名称：</label></dt>
                <dd>
                    <input type="text" id="appName" name="appName"  editable="false" class="easyui-validatebox input_text" required="true" validType="length[1,100]" />
                </dd>
                <dt><label for="appToken">应用token：</label></dt>
                <dd>
                    <input type="text" id="appToken" name="appToken"  editable="true" class="easyui-validatebox input_text" required="true" validType="length[1,100]"/>
                </dd>
                <dt>　</dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div> 
<script>
    var app = new CreateData("#dg","datagrid","/api/app",'id','#dlg','#fm');
    app.getData('app-list');
    $(function(){
        $('#enable').hide();
        $('#dg').datagrid({
            onDblClickRow:function(rowIndex,rowData){
                edit();
            },
            onClickRow:function(rowIndex,rowData){
                if(rowData.p_status==2){
                    $('#disable').hide();
                    $('#enable').show();
                }else{
                    $('#enable').hide();
                    $('#disable').show();
                }
            }
        });
    });
    function format_status(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">启用</span>';
        } else {
        return '<span style="color:red;">停用</span>';
        }
    } 

    function add()
    {
        app.addData('app-add','添加APP');
        $('#appToken').removeAttr('readonly');
    }
    function edit()
    {
        app.editData('app-edit','编辑APP');
        $('#appToken').attr('readonly','readonly');
    }
    function disable()
    {
        app.delData('app-del','disable');
    }

    function enable()
    {
        app.delData('app-del','enable');
    }

    function save()
    {
        app.save();
    }
</script> 
</body>
</html>