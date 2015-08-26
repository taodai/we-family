<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use \kucha\ueditor\UEditor;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
$this->registerJsFile("@web/theme/easyui131/bianjie_staly.js",['position'=>View::POS_END]);
$this->registerJsFile("@web/theme/easyui131/jquery.edatagrid.js",['position'=>View::POS_END]);
$this->registerJsFile("@web/theme/system/helper/dlg_helper.js",['position'=>View::POS_END]);
$this->registerCssFile("@web/theme/system/helper/style.css",['position'=>View::POS_END]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/easyui131/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/easyui131/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/system/helper/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/js/webuploader/webuploader.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/css/common.css">
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/bianjie_staly.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/dlg_helper.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/widget.js"></script>

</head>
<body>

    <table id="datagrid"></table>
    
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="reload()">刷新</a>
    </div>  
    
    

<script>
    var Yii = {
        'add' : '<?php echo Url::toRoute('handle/show-add') ?>',
        'data': '<?php echo Url::toRoute('handle/news-list') ?>',
        'edit': '<?php echo Url::toRoute('handle/show-edit') ?>',
    };
    var url;
    $(function(){
        $('#datagrid').datagrid({
            url : Yii.data,
            fit : true,
            fitColumns : true,
            striped : true,
            rownumbers : true,
            border : false,
            pagination : true,
            pageSize : 20,
            checkOnSelect : true,
            selectOnCheck : true,
            singleSelect : true,
            pageList : [10, 20, 30, 40, 50],
            pageNumber : 1,
            toolbar : '#toolbar',
            columns : [[
                {
                    field : 'id',
                    title : '自动编号',
                    width : 100,
                    checkbox : true
                },
                {
                    field : 'title',
                    title : '标题',
                    width : 100
                },
                {
                    field : 'pirUrl',
                    title : '图片',
                    width : 50,
                    height: 90,
                    formatter : function(val,row){
                        if(row.picUrl==''){
                            return '无图片'
                        }else{
                            return '<img width="50" src="'+row.picUrl+'">'
                        }

                    }
                },
                {
                    field : 'description',
                    title : '描述',
                    width : 100
                },
                {
                    field : 'addtime',
                    title : '添加时间',
                    width : 50
                }


            ]]
        });
        
        
    });

    function reload(){
        $('#datagrid').datagrid('reload');
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
        var url = '<iframe scrolling="auto" frameborder="0"  src="'+Yii.add+'" style="width:100%;height:100%;"></iframe>';
        opentabs("新增图文",url);
        // url = Yii.add;
        // formClear();
        // $('#dlg').dialog('open').dialog('setTitle','新建'); 
        // $('#sex').combobox('select',1);
        // $('#status').combobox('select',1);
    }
    function edit()
    {
        formClear();
        
        var row = $('#datagrid').datagrid('getSelected'); 
        var url = '<iframe scrolling="auto" frameborder="0"  src="'+Yii.edit+'?id='+row.id+'" style="width:100%;height:100%;"></iframe>';
         
        if (row){  
            opentabs("修改图文",url);
            // $('#dlg').dialog('open').dialog('setTitle','编辑');  
            // // 加载数值
            // $('#fm').form('load',row);  
            // url = Yii.edit+"?id="+row.id;
            // var pic = $("#picUrl").val();
            // $("#pic").attr('src',pic);
            // var content = $("#content").val();
            // UE.getEditor('weixinnews-content').setContent(content);
            //$("#weixinnews-content").text(content);
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条记录'  
            });  
        }
    }
    function opentabs(tabname,url){
        var parent_tab = parent.$('#maintab');
        if (parent_tab.tabs('exists', tabname)) {
                parent_tab.tabs('select', tabname);
                var tab = parent_tab.tabs('getSelected');
                parent_tab.tabs('update', {
                    tab: tab,
                    options: {
                        title: tabname,
                        content: url
                    }
                });

            } else {
                parent_tab.tabs('add', {
                    title : tabname,
                    iconCls : 'icon-edit',
                    closable : true,
                    content : url
                });
            }
    }
    
</script>


</body>
</html>
