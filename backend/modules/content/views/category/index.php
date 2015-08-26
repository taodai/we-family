<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<table id="datagrid"></table>


<div id="datagrid_tool" style="padding:5px;">
    <div style="margin-bottom:5px;">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="datagrid_tool.add();">添加</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="datagrid_tool.edit();">修改</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-arrow_switch" plain="true" onclick="datagrid_tool.rank();">排序</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="datagrid_tool.remove();">删除</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="datagrid_tool.reload();">刷新</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="datagrid_tool.redo();">取消选择</a>
    </div>
<!--    <div style="padding:0 0 0 7px;color:#333;">-->
<!--        讲师姓名：<input type="text" class="textbox" name="user" style="width:110px">-->
<!--        创建时间从：<input type="text" name="date_from" class="easyui-datebox" editable="false" style="width:110px">-->
<!--        到：<input type="text" name="date_to" class="easyui-datebox" editable="false" style="width:110px">-->
<!--        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.search();">查询</a>-->
<!--    </div>-->
</div>

<div id="mm" class="easyui-menu" style="width:120px;">
    <div onclick="datagrid_tool.sub_add()" data-options="iconCls:'icon-add'">添加子类</div>
</div>
<form id="datagrid_add" style="margin:0;padding:5px 0 0 25px;color:#333;">
    <input type="hidden" name="id" value="">
    <input type="hidden" name="module" value="<?php echo $module ?>">
    <dl>
        <dt><label>上级分类：</label></dt>
        <dd>
            <select class="easyui-combobox" name="pid" id="select" style="width:204px;">
                <option value="0">顶级分类</option>
                <?php
                if($category) {
                    foreach ($category as $key => $value) {
                        ?>
                        <option value="<?php echo $value['id'] ?>"><?php echo $value['category'] ?></option>
                    <?php }
                }
                ?>
            </select>
        </dd>
        <dt><label>分类名称：</label></dt>
        <dd>
            <input type="text" name="category" class="textbox" style="width:200px;">
        </dd>
        <dt><label>排　　序：</label></dt>
        <dd>
            <input type="text" name="rank" class="textbox" value="0" style="width:200px;">
        </dd>
    </dl>
</form>
<?php $this->beginBlock('script') ?>
<script>
    var Yii = {
        'add' : '<?php echo \yii\helpers\Url::toRoute('category/add') ?>',
        'edit' : '<?php echo \yii\helpers\Url::toRoute('category/edit') ?>',
        'data': '<?php echo \yii\helpers\Url::toRoute('category/data') ?>',
        'rank': '<?php echo \yii\helpers\Url::toRoute('category/rank') ?>',
        'remove': '<?php echo \yii\helpers\Url::toRoute('category/remove') ?>',
        'module': <?php echo $module ?>
    };
</script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/category.js"></script>
<?php $this->endBlock() ?>