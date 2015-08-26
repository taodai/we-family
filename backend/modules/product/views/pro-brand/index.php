
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
<body>
        <table id="brand_dg"  data-options="
                    rownumbers: false,
                    height:'auto',
                    animate: false,
                    collapsible: true,
                    fitColumns: true,
                    idField: 'pc_id',
                    pagination:true,
                    treeField: 'pc_name',
                    toolbar:'#cate_tool',
                    fit:true
                ">
            <thead>
                <tr>
                    <th field="pb_id" width="5" sortable="true">品牌ID</th>
                    <th field="pb_name" width="40">品牌名称</th>
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

    <div id="dlg_cate" class="easyui-dialog singleColumn m-form"
         closed="true">
        <form id="fm_cate" method="post">
<!--            <table class="form_table">-->
<!--                <tr><th>上级分类</th><td><select id="cc"><option value="0">顶级分类</option></select></td></tr>-->
<!--                <tr><th>上级分类</th><td><input type="text" class="easyui-validatebox textbox" required="true" validType="length[1,50]"/></td></tr>-->
<!--            </table>-->
            <dl>
                <dt><label>品牌名称：</label></dt>
                <dd>
                    <input type="text" name="pb_name" class="easyui-validatebox textbox" required="true" style="width:200px;">
                </dd>
                <dt></dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_cate').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div>
<script type="text/javascript">
    var brand = new CreateData('#brand_dg','datagrid','/product/pro-brand','pb_id','#dlg_cate','#fm_cate');
    brand.getData('data');
    function add(){
        brand.addData('add','新增分类');
    }
    function edit(){
        brand.editData('add','编辑模块');
    }
    function destroy(){
        $.messager.alert(
            '警告操作！', '系统默认无法删除！', 'warning'
        );
        //brand.delData('remove');
    }
    function save()
    {
        brand.save();
    }
</script>
</body>
</html>