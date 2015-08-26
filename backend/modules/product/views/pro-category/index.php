
<?php
use backend\assets\AppAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
$this->registerJsFile("@web/theme/easyui131/bianjie_staly.js",['position'=>View::POS_END]);
$this->registerJsFile("@web/theme/easyui131/jquery.edatagrid.js",['position'=>View::POS_END]);
$this->registerJsFile("@web/theme/system/helper/dlg_helper.js",['position'=>View::POS_END]);
$this->registerCssFile("@web/theme/system/helper/style.css",['position'=>View::POS_END]);
?>
<?php $this->beginPage() ?>
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
    <script type="text/javascript" src="<?php echo Url::base(); ?>/js/webuploader/webuploader.html5only.min.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/widget.js"></script>
</head>
<body id="body-layout" class="easyui-layout" style="height:auto;">
    <div data-options="region:'west',split:true" title="产品分类" style="width:20%;">
        <table id="pc_dg"  data-options="
                    rownumbers: false,
                    height:'auto',
                    animate: false,
                    collapsible: true,
                    fitColumns: true,
                    idField: 'pc_id',
                    treeField: 'pc_name',
                    toolbar:'#cate_tool',
                    fit:true
                ">
            <thead>
                <tr>
                    <th field="pc_id" width="20" sortable="true">分类ID</th>
                    <th field="pc_name" width="40">菜单名称</th>
                </tr>
            </thead>
        </table>
        <div id="cate_tool" style="padding:5px;">
            <div style="margin-bottom:5px;">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy()">删除</a>
            </div>
        </div>
    </div>
    <div data-options="region:'center',title:'产品管理'" style="width:80%;">
        <table id="pro_dg" data-options="
                    rownumbers: false,
                    height:'auto',
                    animate: false,
                    collapsible: true,
                    fitColumns: true,
                    idField: 'pc_id',
                    treeField: 'pc_name',
                    toolbar:'#tool-btn',
                    fit:true">
            <tr>
                <th field="pro_id" width="20" sortable="true">分类ID</th>
                <th field="pro_title" width="40">产品标题</th>
                <th field="pro_store" width="40">库存</th>
                <th field="pro_price" width="40">价格</th>
                <th field="pro_pic" width="40">主图</th>
            </tr>
        </table>
        <div id="tool-btn" style="padding:5px;">
            <div style="margin-bottom:5px;">
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="datagrid_tool.add();">添加</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="datagrid_tool.edit();">修改</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="datagrid_tool.remove();">删除</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="datagrid_tool.reload();">刷新</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="datagrid_tool.redo();">取消选择</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="datagrid_tool.reall();">显示全部</a>
                <label style="padding-left: 50px;"><input id="search" data-options="buttonText:'搜索',prompt:'请输入标题'" style="width:250px;height:28px;"></label>
            </div>
        </div>
    </div>
    <div id="dlg_cate" class="easyui-dialog singleColumn m-form"
         closed="true">
        <form id="fm_cate" method="post">
<!--            <table class="form_table">-->
<!--                <tr><th>上级分类</th><td><select id="cc"><option value="0">顶级分类</option></select></td></tr>-->
<!--                <tr><th>上级分类</th><td><input type="text" class="easyui-validatebox textbox" required="true" validType="length[1,50]"/></td></tr>-->
<!--            </table>-->
            <dl>
                <dt><label>上级分类：</label></dt>
                <dd>
                    <select id="cc" name="pc_fid"  style="width:206px;height:26px;"><option value="0">顶级分类</option></select>
                </dd>
                <dt><label>分类名称：</label></dt>
                <dd>
                    <input type="text" name="pc_name" class="easyui-validatebox textbox" required="true" style="width:200px;">
                </dd>
                <dt></dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_cate').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div>
<script type="text/javascript">
    var product = new CreateData('#pro_dg','datagrid','/product/products','pro_id','#dlg_cate','#fm_cate');
    var pro_cate = new CreateData('#pc_dg','treegrid','/product/pro-category','pc_id','#dlg_cate','#fm_cate');
    pro_cate.getData('data');
    product.getData('data');
    $(function(){
        $('#cc').combobox({
            url:'<?php echo Url::toRoute('pro-category/get-cate') ?>',
            valueField:'id',
            textField:'text'
        });
    });

    $('#search').searchbox({
    });
    function add(){
        $('#cc').combobox('reload');
        pro_cate.addData('add','新增分类');
    }
    function edit(){
        pro_cate.editData('add','编辑模块');
    }
    function destroy(){
        pro_cate.delData('remove');
    }
    function save()
    {
        pro_cate.save();
    }
</script>
</body>
</html>