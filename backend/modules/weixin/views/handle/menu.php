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
    <table id="datagrid"></table>
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="reload()">刷新</a>

    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" style="width:600px;height:500px"
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="menu_name">菜单名称：</label></dt>
                <dd>
                    <input id="menu_name"  name="menu_name" class="textbox" data-options="multiple:true,multiline:true" style="width:200px;" />
                </dd>
                <dt><label for="pid">上级菜单：</label></dt>
                <dd>
                    <select  name="pid" id="pid" style="width:204px;" >
                        <option value="0">一级菜单</option>
                        <?php if(!empty($menu)){foreach($menu as $key){ ?>
                            <option value="<?php echo $key->id ?>"><?php echo $key->menu_name; ?></option>
                        <?php }} ?>
                    </select>
                </dd>
                <dt><label for="menu_type">菜单类型：</label></dt>
                <dd>
                    <select   name="menu_type" id="menu_type" style="width:204px;" onchange="changetype()">
                        <option value="click">点击回复消息</option>
                        <option value="view">点击跳转页面</option>
                    </select>
                </dd>
                <dt id="keydt"><label for="key">菜单点击事件：</label></dt>
                <dd>
                    <select  name="key" id="key" style="width:204px;">
                        <?php foreach($keys as $key ){ ?>
                            <option value="<?php echo $key->id ?>"><?php echo $key->key ?></option>
                        <?php } ?>
                    </select>
                </dd>
                <dt id="urldt"><label for="url">菜单跳转路径：</label></dt>
                <dd>
                    <textarea id="url" name="url" class="easyui-validatebox input_text" style="width:400px;height:100px" editable="false"  ></textarea>
                    
                </dd>
                <dt><label for="sort">排序：</label></dt>
                <dd>
                    <input type="text" id="sort" class="textbox"  name="sort" style="width:200px;" editable="false" class="easyui-validatebox input_text" />
                </dd>
                
                <dt><label for="status">菜单状态：</label></dt>
                <dd>
                    <select class="easyui-combobox" name="status" id="status" style="width:204px;">
                        <option value="0">正常</option>
                        <option value="1">停用</option>
                    </select>
                </dd>
                <dt>　</dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div> 

<script>
    var Yii = {
        'add' : '<?php echo Url::toRoute('handle/menu-add') ?>',
        'data': '<?php echo Url::toRoute('handle/menu-list') ?>',
        'edit': '<?php echo Url::toRoute('handle/menu-edit') ?>',
    };
    var url;
    $(function(){
        $('#datagrid').treegrid({
            url : Yii.data,
            idField : 'id',
            treeField : 'menu_name',
            fit : true,
            fitColumns : true,
            striped : true,
            rownumbers : true,
            border : false,
            pagination : false,
            lines : true,
            checkOnSelect : true,
            selectOnCheck : true,
            pageSize : 30,
            pageList : [10, 20, 30, 40, 50],
            pageNumber : 1,
            collapsible: true,
            toolbar : '#toolbar',
            onLoadSuccess: function(rows,param){
                $('#datagrid').treegrid('expandAll');
            },
            columns : [[
                {
                    field : 'id',
                    title : '菜单id',
                    width : 20
                },
                {
                    field : 'menu_name',
                    title : '菜单名称',
                    width : 100
                },
                {
                    field : 'menu_type',
                    title : '菜单类型',
                    width : 100
                },
                {
                    field : 'keyname',
                    title : '菜单key',
                    width : 100
                },
                {
                    field : 'sort',
                    title : '排序',
                    width : 50
                },
                {
                    field : 'url',
                    title : '菜单跳转路径',
                    width : 300
                },
                {
                    field : 'status',
                    title : '状态',
                    width : 50,
                    formatter:function(val,row){
                        if (val == 0) {
                            return '<span style="color:green;">启用</span>';
                        } else if(val == 1) {
                            return '<span style="color:red;">停用</span>';
                        }
                    }
                }
                //{
                //  field : 'rank',
                //  title : '排序',
                //  width : 50,
                 //   formatter : function(value,row,index){
                 //       return '<input class="rank textbox" name="'+row.id+'" value="'+row.rank+'">'
                 //   }
                //}

            ]]
        });
    });
    function reload(){
        $('#datagrid').treegrid('reload');
    }
    function changetype(){
        var type = $("#menu_type").val();
        if(type=='view'){
            $("#key,#keydt").hide();
            $("#url,#urldt").show();
        }else if(type=='click'){
            $("#key,#keydt").show();
            $("#url,#urldt").hide();
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
        url = Yii.add;
        formClear();
        $('#dlg').dialog('open').dialog('setTitle','新建'); 
        $('#sex').combobox('select',1);
        $('#status').combobox('select',1);
    }
    function edit()
    {
        formClear();
        var row = $('#datagrid').datagrid('getSelected');
        if (row){  
            $('#dlg').dialog('open').dialog('setTitle','编辑');  
            // 加载数值
            $('#fm').form('load',row);  
            url = Yii.edit+"?id="+row.id;
            changetype();
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
                    $('#datagrid').treegrid('reload');    // reload the user data  
                    $('#datagrid').treegrid('expandAll');
                }  
            }  
        });  
    }

</script> 

</body>
</html>