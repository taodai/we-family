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
<body class="easyui-layout" style="height:auto;">
    <div data-options="region:'west',split:true" title="用户列表" style="width:50%;height:500px;">
        <table id="dg" pageSize="20" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true" fit="true" >  
            <thead>  
                <tr>
                    <th field="uid" width="50" sortable="true">编号</th>
                    <th field="uname" width="80">登录名称</th>
                    <th field="babyName" width="50">用户昵称</th>
                    <th field="status" width="50" formatter="format_status">状态</th>
                </tr>  
            </thead>  
        </table> 
<!--         
        <div id="toolbar" style="height:auto; padding:5px 12px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add('module')">添加</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit('module')">修改</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy('module')">停用</a>
        </div> -->
    </div>
    <div data-options="region:'center',title:'代理列表'"  id="center">
    <div class="easyui-tabs"  id="tabs" fit="true">
        <div title="二级代理" style="padding:10px;" id="second" fit="true">
            <table id="dg-second" pageSize="20" toolbar="#toolbar2" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" fit="true" > 
                <thead>  
                    <tr>
                        <th field="agency_id" width="20" sortable="true">编号</th>
                        <th field="child_id" width="20">用户ID</th>
                        <th field="uname" width="80">登录名称</th>
                        <th field="babyName" width="80">用户昵称</th>
                    </tr>  
                </thead>  
            </table> 
        </div>
        <div title="三级代理" style="padding:10px;" id="three" fit="true">
            <table id="dg-three" pageSize="20" toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" fit="true" > 
                <thead>  
                    <tr>
                        <th field="agency_id" width="20" sortable="true">编号</th>
                        <th field="child_id" width="20">用户ID</th>
                        <th field="uname" width="80">登录名称</th>
                        <th field="babyName" width="80">用户昵称</th>
                    </tr>  
                </thead>  
            </table> 
        </div>
    </div>
<script>
    var menu;
    var user = new CreateData("#dg","datagrid","/ucenter/user",'uid','#dlg','#fm');
    user.getData('user-list');
    $(function(){
        $('#center').hide();
        $('#dg').datagrid({
            // onDblClickRow:function(rowIndex,rowData){
            //     module.editData('user-edit','编辑模块');
            // },
            onClickRow:function(rowIndex,rowData){
                $('#center').show();
                second = new CreateData("#dg-second","datagrid","/ucenter/agency",'agency_id','','');
                second.getData('agency-list?uid='+rowData.uid+'&level=2');
                three = new CreateData("#dg-three","datagrid","/ucenter/agency",'agency_id','','');
                three.getData('agency-list?uid='+rowData.uid+'&level=3');
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
</script> 
</body>
</html>