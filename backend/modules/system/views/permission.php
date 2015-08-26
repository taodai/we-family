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
<body >

    <table id="dg"
            data-options="  
                rownumbers: false,  
                animate: false,  
                collapsible: true,  
                fitColumns: true,  
                idField: 'pm_id',  
                treeField: 'pm_name',
                toolbar: '#toolbar',
                fit:true
            ">  
        <thead>  
            <tr>
                <th field="pm_id" width="50" sortable="true">模块编号</th>
                <th field="pm_name" width="80">模块名称</th>
                <th field="pm_url" width="120">限制网址</th>
                <th field="pm_isshow" width="120" formatter="format_show">是否展示</th>
                <th field="pm_desc" width="80">权限备注</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="pedit()">修改</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="pdestroy()">删除</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-key" plain="true" onclick="action()">操作权限</a>
    </div>  
    <div id="dlg" class="easyui-dialog singleColumn" 
            closed="true" style="width:auto;">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="pm_name">模块名称：</label></dt>
                <dd>
                    <input type="text" id="pm_name" name="pm_name" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                </dd>
                <dt><label for="pm_parent_id">所属模块：</label></dt>
                <dd>
                    <input id="pm_parent_id" name="pm_parent_id" class="easyui-combotree" editable="false" data-options="url:'<?php echo site_url('system/permission_c/json_parent');?>'"/>
                </dd>
                <dt><label for="pm_url">限制网址：</label></dt>
                <dd>
                    <input id="pm_url" name="pm_url" style="width:300px;" class="easyui-validatebox input_text"/>
                </dd>
                <dt><label for="pm_isshow">是否展示：</label></dt>
                <dd>
                    <select id="pm_isshow" name="pm_isshow" required="true" class="easyui-combobox" editable="false">
                        <option value="1">展示</option>
                        <option value="2">隐藏</option>
                    </select>
                </dd>
                <dt><label for="pm_desc">权限备注：</label></dt>
                <dd>
                    <textarea id="pm_desc" name="pm_desc" style="height:61px;" class="easyui-validatebox input_text" validType="length[1,100]"></textarea>
                </dd>
                <dt>　</dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div>
    <div id="dlg_ac" class="easyui-dialog singleColumn" 
            closed="true" style="width:600px;height:400px;">
        <table id="dg_ac" pageSize="20" class="easyui-datagrid" toolbar="#toolbar2" pagination="true" fitColumns="true" rownumbers="true" singleSelect="true" fit="true">   
            <thead>  
                <tr>
                <th field="mc_name" width="50" > 操作权限 </th>             
                <th field="mc_url" width="50" > 操作地址 </th>  
                <th field="mc_status" width="20"  formatter="mc_status" > 状态 </th>  
                </tr>  
            </thead>  
        </table> 
        
        <div id="toolbar2" style="height:auto; padding:5px 12px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="action_add()">添加</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="action_edit()">修改</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="action_del()">删除</a>
        </div>
    </div>
    <div id="dlg_ac2" class="easyui-dialog singleColumn" 
            closed="true" style="width:auto;">
        <form id="fm_ac" method="post" novalidate>
            <dl>
                <dt><label for="mc_name">权限名称：</label></dt>
                <dd>
                    <input type="text" id="mc_name" name="mc_name" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                </dd>
                <dt><label for="mc_url">限制网址：</label></dt>
                <dd>
                    <input id="mc_url" name="mc_url" class="easyui-combotree" style="width:200px;" />
                    <!-- <input id="mc_url" name="mc_url" class="easyui-validatebox input_text"/> -->
                </dd>
                <dt><label for="mc_status">状态：</label></dt>
                <dd>
                    <select id="mc_status" name="mc_status" required="true" class="easyui-combobox" editable="false">
                        <option value="1">正常</option>
                        <option value="2">禁用</option>
                    </select>
                </dd>
                <dt>    </dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="action_save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_ac2').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div>
</body>
<script>
$(function(){
    $('#mc_url').combotree({
        url:"<?php echo site_url('system/permission_c/get_action')?>",
        onBeforeSelect:function(node){
            if (!$(this).tree('isLeaf', node.target)) {
                return false;
            }
        },
        onClick: function(node) {
            if (!$(this).tree('isLeaf', node.target)) {
                $('#mc_url').combo('showPanel');
            }
        }
    });
});
    var url;

    function action()
    {
        check_permission('system/permission_c/json_actions');
        if(!checkpermission){return false;}
        var row = $('#dg').datagrid('getSelected');  
        if (row){
            if(row.pm_parent_id > 0){
                $('#dlg_ac').dialog('open').dialog('setTitle','<font color="red">'+row.pm_name+'</font>'+'-操作权限列表');  
                // 更改URL
                $('#dg_ac').datagrid({
                    url:"<?php echo site_url('system/permission_c/json_actions'); ?>?pid="+row.pm_id
                });
            }else{
                $.messager.show({  
                    title: '错误提示',  
                    msg: '顶级模块无需添加操作权限'  
                });  
            }
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择模块'  
            });  
        }
    }

    function action_add()
    {
        check_permission('system/permission_c/action_add');
        if(!checkpermission){return false;}
        var formObject = $('#fm_ac');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
        $('#dlg_ac2').dialog('open').dialog('setTitle','新增操作权限');  
        $('#mc_status').combobox('select',1);
        var row = $('#dg').datagrid('getSelected'); 
        // 加载数值
        // formObject.form('load',row);  
        // 更改URL
        url = "<?php echo site_url('system/permission_c/action_add'); ?>?pm_id="+row.pm_id;  
    }
    function action_edit()
    {
        check_permission('system/permission_c/action_update');
        if(!checkpermission){return false;}
        var formObject = $('#fm_ac');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
        var row = $('#dg_ac').datagrid('getSelected');  
        if (row){
            $('#dlg_ac2').dialog('open').dialog('setTitle','编辑操作权限');  
            // 加载数值
            formObject.form('load',row);  
            // 更改URL
            url = "<?php echo site_url('system/permission_c/action_update'); ?>?mc_id="+row.mc_id;  
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条操作权限'  
            });  
        }
    }

    function action_save(){
        var mc_url =  $("#mc_url").combotree("getText");
        $("#mc_url").combotree("setValue",mc_url);
        $('#fm_ac').form('submit',
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
                    $('#dlg_ac2').dialog('close');      // close the dialog  
                    $('#dg_ac').datagrid('reload');    // reload the user data  
                }  
            }  
        });  
    }

    function action_del(){  
        check_permission('system/permission_c/action_delete');
        if(!checkpermission){return false;}
        var row = $('#dg_ac').datagrid('getSelected');  
        if (row){  
            $.messager.confirm('确认','确定停用操作权限：<font color="red">' + row.mc_name + '</font>?',function(r){  
                if (r){  
                    $.post(
                        '<?php echo site_url("system/permission_c/action_delete"); ?>'
                        ,{mc_id:row.mc_id},function(result){  
                        if (result.success){  
                            $('#dg_ac').datagrid('reload');    // reload the user data  
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

    Mutual.gridType = "treegrid";
    Mutual.init("<?php echo site_url('system/permission_c');?>", "pm_id");

    function format_show(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">启用</span>';
        } else {
        return '<span style="color:red;">停用</span>';
        }
    }

    function mc_status(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">正常</span>';
        } else {
        return '<span style="color:red;">禁用</span>';
        }
    }  

    function add()
    {
        check_permission('system/permission_c/json_insert');
        if(!checkpermission){return false;}
        Mutual.add();
        $('#pm_isshow').combobox('select',1); 
        var row = $(Mutual.datagrid).datagrid('getSelected');  
        if (row){
            $('#pm_parent_id').combotree('setValue',row.pm_id);
        }
    }
    
    function pedit()
    {
        check_permission('system/permission_c/json_update');
        if(!checkpermission){return false;}
        Mutual.edit();
    }
    function pdestroy()
    {
        check_permission('system/permission_c/json_delete');
        if(!checkpermission){return false;}
        Mutual.edit();
    }

    function save(){
        var mThis = Mutual;
        $(mThis.dlg_form).form('submit',{  
            url: mThis.url,  
            // onSubmit: function(){
            //     $('#sp_permission_url').val($('#sp_url_id').combotree("getText"));
            //     // alert($('#url').combotree("getText")); 
            //     return $(this).form('validate');  
            // },  
            success: function(result){
                var result = eval('('+result+')'); 
                if (result === true){
                    $("#pm_parent_id").combotree("reload");
                    $(mThis.dlg).dialog('close');
                    if (mThis.gridType == "datagrid") {
                        $(mThis.datagrid).datagrid("reload");
                    } else if(mThis.gridType == "treegrid") {
                        $(mThis.datagrid).treegrid("reload");
                    }
                } else if(result === false) {
                            $.messager.show({  
                                title: '错误提示', 
                                msg: '操作失败' 
                            });
                } else  {
                    if(result.errorMsg =='' || result.errorMsg ==undefined || result.errorMsg == null){
                        $.messager.show({
                            title: '错误提示',
                            msg: result.toString() 
                        });
                    }else{
                        $.messager.show({  
                            title: '错误提示',  
                            msg: result.errorMsg 
                        });
                    }
                }  
            }  
        });
    }
</script> 
</html>  