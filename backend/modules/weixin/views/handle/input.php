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
                <th field="id" width="10" sortable="true">编号</th>
                <th field="input" width="10">输入消息</th>
                <th field="typename" width="20">回复类型</th>
                <th field="title" width="20" >回复标题</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="reload()">刷新</a>

    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" style="width:600px;height:500px"
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="input">输入消息：</label></dt>
                <dd>
                    <input id="input"  name="input" class="textbox" data-options="multiple:true,multiline:true" style="width:200px;" />
                </dd>
                <dt><label for="type">回复类型：</label></dt>
                <dd>
                    <select   name="type" id="type" style="width:204px;" onchange="changetype()">
                        <option value="1">回复消息</option>
                        <option value="2">回复图文</option>
                    </select>
                </dd>
                <dt id="news_iddt"><label for="news_id">发送图文：</label></dt>
                <dd>
                    <select  name="news_id" id="news_id" style="width:204px;" >
                        <?php foreach($news as $key){ ?>
                            <option value="<?php echo $key->id ?>"><?php echo $key->title ?></option>
                        <?php } ?>
                    </select>
                </dd>
                <dt id="message_iddt"><label for="message_id">发送消息：</label></dt>
                <dd>
                    <select  name="message_id" id="message_id" style="width:204px;" >
                        <?php foreach($message as $key){ ?>
                            <option value="<?php echo $key->id ?>"><?php echo $key->title ?></option>
                        <?php } ?>
                    </select>
                    
                </dd>
                <dt>　</dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
                </dd>
            </dl>
        </form>
    </div> 

<script>
    var Yii = {
        'add' : '<?php echo Url::toRoute('handle/input-add') ?>',
        'list': '<?php echo Url::toRoute('handle/input-list') ?>',
        'edit': '<?php echo Url::toRoute('handle/input-edit') ?>',
    };
    var url;
    $(function(){
        $('#dg').datagrid({
            url:Yii.list,
            onDblClickRow:function(rowIndex,rowData){
                edit();
            }
        });

    });
    function reload(){
        $('#dg').datagrid('reload');
    }
    function changetype(){
        var type = $("#type").val();
        if(type=='1'){
            $("#news_id,#news_iddt").hide();
            $("#message_id,#message_iddt").show();
        }else if(type=='2'){
            $("#news_id,#news_iddt").show();
            $("#message_id,#message_iddt").hide();
        }
    }

    function formClear()
    {
        var formObject = $('#fm');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
        $("#type").val("1");
    }

    
    function add()
    {
        url = Yii.add;
        formClear();
        $('#dlg').dialog('open').dialog('setTitle','新建'); 
        $('#sex').combobox('select',1);
        $('#status').combobox('select',1);
        changetype();
    }
    function edit()
    {
        formClear();
        var row = $('#dg').datagrid('getSelected');  
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
                    $('#dg').datagrid('reload');    // reload the user data  
                }  
            }  
        });  
    }

</script> 

</body>
</html>