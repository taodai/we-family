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
    <table id="dg"
            data-options="  
                rownumbers: false,  
                animate: false,  
                collapsible: true,  
                fitColumns: true,  
                idField: 'sm_id',  
                treeField: 'sm_menu_title',
                fit:true,
                toolbar:'#toolbar'
            ">  
        <thead>  
            <tr>
                <th field="sm_id" width="50" sortable="true">编号</th>
                <th field="sm_menu_title" width="80">菜单名称</th>
                <th field="sm_menu_url" width="120">网址</th>
                <th field="sm_weight" width="50">显示顺序</th>
                <th field="sm_menu_png" width="50" formatter="format_img">图标</th>
                <th field="sm_tag" width="50" >所属模块</th>
                <th field="sm_status" width="50" formatter="format_status">状态</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="medit()">修改</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="mdestroy()">停用</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="reloadmenu()">重载左侧导航</a>
    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" 
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="sm_menu_title">菜单名称：</label></dt>
                <dd>
                    <input type="text" id="sm_menu_title" name="sm_menu_title" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                </dd>
                <dt><label for="sm_menu_url">网址：</label></dt>
                <dd>
                    <input type="text" id="sm_menu_url" name="sm_menu_url" style="width:200px;" editable="false" class="easyui-validatebox input_text" validType="length[1,100]" />
                </dd>
                <dt><label for="sm_parent_id">上级菜单：</label></dt>
                <dd>
                    <input id="sm_parent_id" name="sm_parent_id" class="easyui-combotree" required="true" editable="false"  data-options="url:'<?php echo Url::home().'/system/menu/parent-menu';?>'"/>
                </dd>
                <dt><label for="sm_menu_png">图标：</label></dt>
                <dd>
                    <input id="sm_menu_png" name="sm_menu_png" class="easyui-combobox" editable="false" style="width:156px;">
                </dd>
                <dt><label for="sm_weight">显示顺序：</label></dt>
                <dd>
                    <input id="sm_weight" name="sm_weight" class="easyui-validatebox input_text" editable="false" style="width:156px;">
                </dd>
                <dt><label for="sm_tag">模块：</label></dt>
                <dd>
                    <input id="sm_tag" name="sm_tag" class="easyui-validatebox input_text" editable="false" style="width:156px;">
                </dd>
                <dt><label for="sm_status">状态：</label>
                <dd><select id="sm_status" name="sm_status" class="easyui-combobox" editable="false">
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
        $('#dg').treegrid({
            url:'/system/menu/list',
            onDblClickRow:function(rowIndex,rowData){
                medit();
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

    function format_img(val,row)
    {
        if(row.sm_menu_png){
            return '<img src="<?php  echo Url::base();?>/theme/easyui131/themes/icons/'+row.sm_menu_png+'" />';
        }
    } 

    function formClear()
    {
        var formObject = $('#fm');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
    }

    // Mutual.gridType = "treegrid";
    // Mutual.init("<?php echo Url::home().'/system/menu_c';?>", "sm_id");
    
    function add()
    {
        url = "/system/menu/menu-add";
        formClear();
        $('#dlg').dialog('open').dialog('setTitle','新建'); 
        $('#sm_status').combobox('select',1);
    }
    function medit()
    {
        formClear();
        var row = $('#dg').datagrid('getSelected');  
        if (row){  
            $('#dlg').dialog('open').dialog('setTitle','编辑');  
            // 加载数值
            $('#fm').form('load',row);  
            url = "/system/menu/menu-edit?sm_id="+row.sm_id;
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条记录'  
            });  
        }
    }
    function mdestroy()
    {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            if(row.sm_parent_id == 0){
                $.messager.show({  
                    title: '错误提示',  
                    msg: row.sm_menu_title+'该顶级菜单下所有子菜单也将被停用'  
                }); 
                // return false;
            }  
            $.messager.confirm('确认','确定停用菜单：' + row.sm_menu_title + '?',function(r){  
                if (r){  
                    $.post(
                        '<?php echo Url::home(); ?>/system/menu/menu-del'
                        ,{sm_id:row.sm_id},function(result){  
                        if (result.suc){  
                            $('#dg').treegrid('reload');    // reload the user data  
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
                    $('#dg').treegrid('reload');    // reload the user data  
                }  
            }  
        });  
    }
    $(function(){
        $('#sm_menu_png').combogrid({  
            panelWidth:200,  
            idField:'name',  
            textField:'name',  
            url:"<?php echo Url::home();?>/system/menu/png-list",  
            fitColumns:true,
            columns:[[  
                {field:'name',title:'名称',width:140},  
                {field:'image',title:'图片',width:60,align:'center',formatter:function(value,row,index){return '<img src="'+row.image+'" />';}}
            ]] 
        }); 
    });
    function reloadmenu()
    {
        top.location.reload();
    }
</script> 
</body>
</html>