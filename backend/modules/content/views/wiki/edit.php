<?php
use yii\helpers\Url;
use \kucha\ueditor\UEditor;
use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<div id="" class="easyui-panel" title="修改百科">
    <form id="ajaxForm" action="<?php echo Url::toRoute('wiki/add') ?>" method="post" style="margin:0;padding:5px 0 0 25px;color:#333;">
        <input type="hidden" name="id" value="<?php echo $wiki['id'] ?>">
        <div class="table-responsive">
            <table class="m-table" style="width: 95%">
                <tr><th width="100">标题：</th><td><input type="text" name="title" class="textbox" value="<?php echo $wiki['title'] ?>"></td></tr>
                <tr><th>百科分类：</th><td><select name="category" id="select" style="width:320px;height:27px;">
                            <option value="">请选择分类</option>

                        </select></td></tr>
                <tr><th>tag标签：</th><td><input type="text" name="tag" class="textbox" value="<?php echo $wiki['tag'] ?>" ><span style="padding-left: 24px;">多个标签以（,）隔开；最多支持5个标签</span></td></tr>
                <tr><th>首页大图：</th>
                    <td>
                        <input type="hidden" name="picUrl" value="<?php echo $wiki['picUrl'] ?>" >
                        <input type="hidden" name="pic" value="<?php echo $wiki['pic'] ?>" >
                        <div id="uploader-demo" style="width:320px;display:inline-block;vertical-align: middle;">
                            <!--用来存放item-->
                            <div id="fileList" class="uploader-list">
                                <div class="file-item thumbnail upload-state-done">
                                    <img src="<?php echo $wiki['picUrl'] ?>" width="312" height="152" />
                                </div>
                            </div>
                            <div id="filePicker" style="width:295px;">选择图片</div>
                        </div>
                        <span style="padding-left:24px;">图片比例为2：1（建议大小640X320）,图片大小不得超过200KB</span>
                    </td>
                </tr>
                <tr><th>月份关联：</th><td> <input id="month" class="textbox" name="month" data-options="multiple:true,multiline:true" style="width:320px;height:80px;padding:2px" /></td></tr>
                <tr><th>概述：</th><td>
                        <textarea name="info" class="textbox" style="width:320px;height:90px"><?php echo $wiki['info'] ?></textarea>
                        </td></tr>
                <tr><th>内容：</th>
                    <td>
                        <?php
                        echo \kucha\ueditor\UEditor::widget([
                            'model'=>$model,
                            'attribute'=>'content',
                            'clientOptions' => [
                                'initialContent'=>$wiki['content'],
                                'enableAutoSave'=>false,
                                'autoHeightEnabled'=>false,
                                //编辑区域大小
                                'initialFrameHeight' => '240',
                                //设置语言
                                'lang' =>'zh-cn', //中文为 zh-cn
                            ]]);
                        ?>
                    </td>
                </tr>
                <tr><td colspan="2">
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" id="st">保存</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel">取消</a>
                    </td>
                </tr>
            </table>
        </div>
</div>
<?php $this->beginBlock('script') ?>
<script>
    var Yii = {
        'index':'<?php echo \yii\helpers\Url::toRoute('wiki/index') ?>',
        'add' : '<?php echo \yii\helpers\Url::toRoute('wiki/add') ?>',
        'data': '<?php echo \yii\helpers\Url::toRoute('wiki/data') ?>',
        'rank': '<?php echo \yii\helpers\Url::toRoute('wiki/rank') ?>',
        'remove': '<?php echo \yii\helpers\Url::toRoute('wiki/remove') ?>',
        'swf': '<?php echo Url::base(); ?>/js/webuploader/Uploader.swf'
    };
    var month = <?php echo $month ?>;
    var category = <?php echo $category ?>;
    flag = false;
    $('#select').combotree({
        lines : true,
        data   : category,
        checkbox : true,
        onBeforeSelect: function(node) {
            var isLeaf = $(this).tree('isLeaf', node.target);
            if (!isLeaf) {
                $.messager.alert(
                    '警告操作！', '请选择子分类！', 'warning'
                );
                // 返回false表示取消本次选择操作
                return false;
            }
        }
    });
    $('#select').combotree('setValue','<?php echo $wiki['category'] ?>');

    $('#month').combotree({
        lines : true,
        data   : month,
        multiple : true,
        checkbox : true,
        onLoadSuccess : function (node, data) {
            var _this = this;
            if (data) {
                $(data).each(function (index, value) {
                    if (this.state == 'closed') {
                        $(_this).tree('collapseAll');
                    }
                });
            }
        }

    });

</script>

<script type="text/javascript" src="<?php echo Url::base(); ?>/js/webuploader.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/wiki.js"></script>
<?php $this->endBlock() ?>