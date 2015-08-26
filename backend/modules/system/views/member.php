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
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/dlg_helper.js"></script> 
</head>
<body>
    <table id="dg" pageSize="20" toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" fit="true" 
    >  
        <thead>  
            <tr>
                <th field="mid" width="20" sortable="true">编号</th>
                <th field="login_name" width="80">登录用户名</th>
                <th field="real_name" width="80">真实姓名</th>
                <th field="sex" width="20" formatter="format_male">性别</th>
                <th field="mobile" width="50">手机</th>
                <th field="weixin" width="50">微信号</th>
                <th field="status" width="50" formatter="format_status">状态</th>
                <!-- <th field="create_mid" width="50" >创建人员</th> -->
                <th field="createtime" width="50" >创建时间</th>
                <th field="logintime" width="50" >最后登录时间</th>
                <th field="loginip" width="50" >登录IP</th>
                <th field="logintimes" width="50" >登录次数</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy()">停用</a>
<!--         <form id="search_form">
            <label>员工姓名：<input type="text" class="input_text" name="st_realname" /></label>     
            &nbsp;&nbsp;<label>入职日期：<input class="easyui-datebox" style="width:100px" name="st_entrydate[]" /> - <input class="easyui-datebox" style="width:100px" name="st_entrydate[]" /></label> 
            &nbsp;&nbsp;<label>是否在职：<select name="st_status" class="easyui-combobox" editable="false">
                        <option value="0" selected="selected" >全部</option>
                        <option value="1" >在职</option>
                        <option value="2">离职</option></select></label>
            <br/> 
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="Mutual.searchLoad()">查询</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="Mutual.searchReset()">重置</a>
        </form>  --> 
    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" 
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="login_name">用户名：</label></dt>
                <dd>
                    <input type="text" id="login_name" name="login_name" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                </dd>
                <dt><label for="real_name">真实姓名：</label></dt>
                <dd>
                    <input type="text" id="real_name" name="real_name"  editable="false" class="easyui-validatebox input_text" required="true" validType="length[1,100]" />
                </dd>
                <dt><label for="mobile">手机号码：</label></dt>
                <dd>
                    <input id="mobile" name="mobile" class="easyui-validatebox input_text" editable="false" required="true" validType="mobile">
                </dd>
                <dt><label for="weixin">微信号：</label></dt>
                <dd>
                    <input id="weixin" name="weixin" class="easyui-validatebox input_text" editable="false">
                </dd>
                <dt><label for="sex">性别：</label>
                <dd><select id="sex" name="sex" class="easyui-combobox" editable="false">
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </dd>
                <dt><label for="status">状态：</label>
                <dd><select id="status" name="status" class="easyui-combobox" editable="false">
                        <option value="1">正常</option>
                        <option value="2">停用</option>
                    </select>
                </dd>  
                <dt>　</dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div> 
<script>
    var url;
    $(function(){
        $('#dg').datagrid({
            url:'/system/manager/manager-list',
            onDblClickRow:function(rowIndex,rowData){
                edit();
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
    function format_male(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">男</span>';
        } else {
        return '<span style="color:red;">女</span>';
        }
    } 


    function formClear()
    {
        var formObject = $('#fm');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
    }

    
    function add()
    {
        url = "/system/manager/manager-add";
        formClear();
        $('#dlg').dialog('open').dialog('setTitle','新建'); 
        $('#sex').combobox('select',1);
        $('#status').combobox('select',1);
    }
    function edit()
    {
        formClear();
        var row = $('#dg').datagrid('getSelected');  
        if (row){  
            $('#dlg').dialog('open').dialog('setTitle','编辑');  
            // 加载数值
            $('#fm').form('load',row);  
            url = "/system/manager/manager-edit?mid="+row.mid;
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条记录'  
            });  
        }
    }
    function destroy()
    {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('确认','确定停用用户：' + row.real_name + '?',function(r){  
                if (r){  
                    $.post(
                        '<?php echo Url::home(); ?>/system/manager/manager-del'
                        ,{mid:row.mid},function(result){  
                        if (result.suc){  
                            $('#dg').datagrid('reload');    // reload the user data  
                        } else {  
                            $.messager.show({   // show error message  
                                title: '错误提示',  
                                msg: result.errorMsg  
                            });  
                        }  
                    },'json');  
                }  
            });  
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条记录'  
            });  
        }
    }

    function save()
    {
        $('#fm').form('submit',
            {  
            url: url,  
            success: function(result){  
                var result = eval('('+result+')');  
                if (result.errorMsg){  
                    $.messager.show({  
                        title: '错误提示',  
                        msg: result.errorMsg  
                    });  
                } else {  
                    $('#dlg').dialog('close');      // close the dialog  
                    $('#dg').datagrid('reload');    // reload the user data  
                }  
            }  
        });  
    }
</script> 
</body>
</html>