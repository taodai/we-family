
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
    <style type="text/css">
        .datagrid-cell a{
            color: #000;
            text-decoration:none;
        }
    </style>
</head>
<body id="body-layout" class="easyui-layout" style="height:auto;">
    <div data-options="region:'west',split:true" title="商品分类" style="width:20%;">
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
                    <th field="pc_name" width="40">商品分类</th>
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
    <div data-options="region:'center',title:'商品管理'" style="width:80%;">
        <table id="pro_dg" data-options="
                    rownumbers: false,
                    height:'auto',
                    animate: false,
                    collapsible: true,
                    fitColumns: true,
                    pagination:true,
                    toolbar:'#tool-btn',
                    fit:true">
            <thead>
                <tr>
                    <th field="pro_id" width="10">商品ID</th>
                    <th field="pro_title" width="40">商品标题</th>
                    <th field="pro_sn" width="10">货号</th>
                    <th field="pro_image_default" width="10" formatter="format_img">主图</th>
                    <th field="pro_cat" width="10" formatter="format_cat">分类</th>
                    <th field="pro_store" width="10">库存</th>
                    <th field="pro_price" width="10">价格</th>
                    <th field="property" width="20" formatter="format_attr">属性</th>
                    <th field="pro_status" width="10" formatter="format_status">状态</th>
                </tr>
            </thead>
        </table>
        <div id="tool-btn" style="padding:5px;">
            <div style="margin-bottom:5px;">
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="datagrid_tool.add();">添加</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="datagrid_tool.edit();">修改</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="datagrid_tool.remove();">删除</a>
                <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="datagrid_tool.reload();">刷新</a>
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
    var Yii = {
        'add' : '<?php echo Url::toRoute('products/showadd') ?>',
        'data': '<?php echo Url::toRoute('products/data') ?>',
        'remove': '<?php echo Url::toRoute('products/remove') ?>'
    };

    var product = new CreateData('#pro_dg','datagrid','/product/products','pro_id');
    var pro_cate = new CreateData('#pc_dg','treegrid','/product/pro-category','pc_id','#dlg_cate','#fm_cate');
    pro_cate.getData('data');
    product.getData('data');
    $('#pro_dg').datagrid( {
            onLoadSuccess:function(data){
                $('.easyui-tooltip').tooltip({});
            }
        }
    );
    $(function(){
        $('#cc').combobox({
            url:'<?php echo Url::toRoute('pro-category/get-cate') ?>',
            valueField:'id',
            textField:'text'
        });

        var url = Yii.data;
        //筛选
        $('#pc_dg').treegrid(
            {
                url:'<?php echo Url::toRoute('pro-category/data') ?>',
                onClickRow :function(row,param){
                    if(url.indexOf('?')>0){
                        dataUrl = url+'&category='+row.pc_id;
                    }else{
                        dataUrl = url+'?category='+row.pc_id;
                    }
                    $('#pro_dg').datagrid( {
                            url : dataUrl
                        }
                    );
                    $('#body-layout').layout('panel','center').panel({title:'商品管理——'+row.pc_name});
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
                $('#pro_dg').datagrid( {
                        url : url
                    }
                );
            }
        });


    });

    $('#search').searchbox({
    });
    function add(){
        $('#cc').combobox('reload');
        pro_cate.addData('add','新增分类');
    }
    function edit(){
        pro_cate.editData('add','编辑分类');
    }
    function destroy(){
        pro_cate.delData('remove');
    }
    function save()
    {
        pro_cate.save();
    }
    //格式化
    function format_status(val,row)
    {
        if (val == 1) {
            return '<span style="color:green;">上架</span>';
        } else if(val == 2){
            return '<span style="color:red;">下架</span>';
        }else{
            return '<span style="color:blue;">无货</span>';
        }
    }

    function format_attr(val,row){
        return '<a href="#" title="'+val+'" class="easyui-tooltip">'+val+'</a>';
    }

    function format_cat(val,row){
        return row.cate.pc_name;
    }
    function format_brand(val,row){
        return row.brand.pb_name;
    }
    function format_img(val,row)
    {
        return '<img width="50" height="50" src="<?php echo Url::base();?>/'+row.pro_prefix+row.pro_image_default+'" />';

    }
    datagrid_tool = {
        reload : function () {
            $('#pro_dg').datagrid('reload');
        },
        reall : function () {
            $('#pro_dg').datagrid('unselectAll');
            $('#body-layout').layout('panel','center').panel({title:'商品管理'});
            $('#search').searchbox('setValue','');
            $('#pro_dg').datagrid({
                url:Yii.data
            });
            url = Yii.data;
        },
        redo : function () {
            $('#pro_dg').datagrid('unselectAll');
        },
        add : function () {
            if (parent.$('#maintab').tabs('exists', '添加产品')) {
                parent.$('#maintab').tabs('select', '添加产品');
            } else {
                parent.$('#maintab').tabs('add', {
                    title : '添加产品',
                    iconCls : 'icon-add',
                    closable : true,
                    content :'<iframe scrolling="auto" frameborder="0"  src="'+Yii.add+'" style="width:100%;height:100%;"></iframe>',
                    url:Yii.add
                });
            }
        },
        remove : function () {
            var rows = $('#pro_dg').datagrid('getSelections');
            if (rows.length > 0) {
                $.messager.confirm('确定操作', '您正在要删除所选的记录吗？', function (flag) {
                    if (flag) {
                        var ids = [];
                        for (var i = 0; i < rows.length; i ++) {
                            ids.push(rows[i].pro_id);
                        }
                        $.ajax({
                            type : 'POST',
                            url : Yii.remove,
                            data : {
                                id : ids
                            },
                            beforeSend : function () {
                                $('#pro_dg').datagrid('loading');
                            },
                            success : function (data) {
                                if (data) {
                                    $('#pro_dg').datagrid('loaded');
                                    $('#pro_dg').datagrid('load');
                                    $('#pro_dg').datagrid('unselectAll');
                                    $.messager.show({
                                        title : '提示',
                                        msg : data + '个数据被删除成功！'
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                $.messager.alert('提示', '请选择要删除的记录！', 'info');
            }
        },
        edit : function () {
            var parent_tab = parent.$('#maintab');

            var rows = $('#pro_dg').datagrid('getSelections');

            if (rows.length > 1) {
                $.messager.alert('警告操作！', '编辑记录只能选定一条数据！', 'warning');
            } else if (rows.length == 1) {
                var url = '<iframe scrolling="auto" frameborder="0"  src="'+Yii.add+'?id='+rows[0].pro_id+'" style="width:100%;height:100%;"></iframe>';
                if (parent_tab.tabs('exists', '修改商品')) {
                    parent_tab.tabs('select', '修改商品');
                    var tab = parent_tab.tabs('getSelected');
                    parent_tab.tabs('update', {
                        tab: tab,
                        options: {
                            title: '修改商品',
                            content: url
                        }
                    });

                } else {
                    parent_tab.tabs('add', {
                        title : '修改商品',
                        iconCls : 'icon-edit',
                        closable : true,
                        content : url
                    });
                }
            } else if (rows.length == 0) {
                $.messager.alert('警告操作！', '编辑记录至少选定一条数据！', 'warning');
            }

        }
    };
</script>
</body>
</html>