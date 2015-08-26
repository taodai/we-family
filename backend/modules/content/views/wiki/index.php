
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
    <script>
        var upload = '<?php echo Url::toRoute('lecturer/uploadfile')?>';
    </script>
</head>
<body id="body-layout" class="easyui-layout" style="height:auto;">
    <div data-options="region:'west',split:true" title="分类" style="width:20%;">
        <table id="category_datagrid" data-options="
                    rownumbers: false,
                    height:'auto',
                    animate: false,
                    collapsible: true,
                    fitColumns: true,
                    idField: 'id',
                    treeField: 'category',
                    fit:true
                ">
            <thead>
                <tr>
                    <th field="id" width="20" sortable="true">分类ID</th>
                    <th field="category" width="40">菜单名称</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'center',title:'百科'"  style="width:80%;">
        <table id="datagrid"></table>
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
<script type="text/javascript">
    var Yii = {
        'add' : '<?php echo Url::toRoute('wiki/showadd') ?>',
        'edit' : '<?php echo Url::toRoute('wiki/showedit') ?>',
        'data': '<?php echo Url::toRoute('wiki/data') ?>',
        'rank': '<?php echo Url::toRoute('wiki/rank') ?>',
        'remove': '<?php echo Url::toRoute('wiki/remove') ?>',
        'month' : '<?php echo Url::toRoute('wiki/getmonth') ?>'
    };
    url = Yii.data;
    $(function(){
        $('#category_datagrid').treegrid(
            {
                url:'<?php echo Url::toRoute('category/data') ?>',
                queryParams : {
                    'module' : 2
                },
                onClickRow :function(row,param){

                    if(url.indexOf('?')>0){
                        url = url+'&category='+row.id;
                    }else{
                        url = url+'?category='+row.id;
                    }
                    $('#datagrid').datagrid( {
                            url : url

                        }
                    );
                    $('#body-layout').layout('panel','center').panel({title:'百科管理——'+row.category});
                }
            }
        );
        $('#search').searchbox({
            searcher:function(value){
                if(url.indexOf('?')>0){
                    url = url+'&title='+value;
                }else{
                    url = url+'?title='+value;
                }
                $('#datagrid').datagrid( {
                        url : url
                    }
                );
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/wiki.js"></script>
</body>
</html>