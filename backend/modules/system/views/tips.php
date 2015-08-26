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
                <th field="id" width="20" sortable="true">编号</th>
                <th field="month" width="20">月份编号</th>
                <th field="montharea" width="20">月份</th>
                <th field="notice" width="80" >妈妈手册</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>  

    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" style="width:600px;height:400px"
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="month">月份：</label></dt>
                <dd>
                    <input id="month" class="textbox" name="month" data-options="multiple:true,multiline:true" style="width:320px;height:30px;padding:2px" />
                    <!-- <input type="text" id="month" name="month" class="easyui-validatebox input_text" required="true" validType="length[1,50]" /> -->
                </dd>
                <dt><label for="notice">妈妈手册：</label></dt>
                <dd>
                    <textarea id="notice" name="notice" class="easyui-validatebox input_text" style="width:400px;height:100px" editable="false" required="true" ></textarea>
                    
                </dd>
                  
                <dt>　</dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div> 
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/wiki.js"></script>
<script>
    var Yii = {
        'add' : '<?php echo \yii\helpers\Url::toRoute('wiki/add') ?>',
        'data': '<?php echo \yii\helpers\Url::toRoute('wiki/data') ?>',
        'rank': '<?php echo \yii\helpers\Url::toRoute('wiki/rank') ?>',
        'remove': '<?php echo \yii\helpers\Url::toRoute('wiki/remove') ?>',
        'month' : '<?php echo Url::toRoute('/system/tips/get-month') ?>'
    };
    var url;
    $(function(){
        combotree('month',false,false,false);
        $('#dg').datagrid({
            url:'<?php echo Url::base()?>/system/tips/tips-list',
            onDblClickRow:function(rowIndex,rowData){
                edit();
            }
        });
    });


    function formClear()
    {
        var formObject = $('#fm');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
    }

    
    function add()
    {
        url = "<?php echo Url::base()?>/system/tips/tips-add";
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
            url = "<?php echo Url::base()?>/system/tips/tips-edit?id="+row.id;
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
                //alert(result);return false;
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