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
    <table id="dg" pageSize="20" toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" fit="true" >  
        <thead>  
            <tr>
                <th field="ut_id" width="50" sortable="true">ID</th>
                <th field="ut_name" width="80">类型名称</th>
                <th field="ut_weight" width="50">显示顺序</th>
                <th field="ut_status" width="50" formatter="format_status">状态</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy()">停用</a>
    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" 
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="ut_name">类型名称：</label></dt>
                <dd>
                    <input type="text" id="ut_name" name="ut_name" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                </dd>
                <dt><label for="ut_weight">显示顺序：</label></dt>
                <dd>
                    <input id="ut_weight" name="ut_weight" class="easyui-validatebox input_text" editable="false" style="width:156px;">
                </dd>
                <dt><label for="ut_status">状态：</label>
                <dd><select id="ut_status" name="ut_status" class="easyui-combobox" editable="false">
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
            url:'/ucenter/type/type-list',
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


    function formClear()
    {
        var formObject = $('#fm');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
    }

    
    function add()
    {
        url = "/ucenter/type/type-add";
        formClear();
        $('#dlg').dialog('open').dialog('setTitle','新建'); 
        $('#ut_status').combobox('select',1);
    }
    function edit()
    {
        formClear();
        var row = $('#dg').datagrid('getSelected');  
        if (row){  
            $('#dlg').dialog('open').dialog('setTitle','编辑');  
            // 加载数值
            $('#fm').form('load',row);  
            url = "/ucenter/type/type-edit?ut_id="+row.ut_id;
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
            $.messager.confirm('确认','确定停用模块：' + row.ut_name + '?',function(r){  
                if (r){  
                    $.post(
                        '<?php echo Url::home(); ?>/ucenter/type/type-del'
                        ,{ut_id:row.ut_id},function(result){  
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