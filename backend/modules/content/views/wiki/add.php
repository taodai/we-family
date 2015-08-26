<?php
use yii\helpers\Url;
use \kucha\ueditor\UEditor;
use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<div id="" class="easyui-panel" title="新增百科">
    <form id="ajaxForm" action="<?php echo Url::toRoute('wiki/add') ?>" method="post" style="margin:0;padding:5px 0 0 25px;color:#333;">
        <div class="table-responsive">
            <table class="m-table" style="width: 95%">
                <tr><th width="100">标题：</th><td><input type="text" id="title" name="title" class="textbox" title="" value=""><span style="color:#A94442;padding-left:12px;" id="title-tip"></span></td></tr>
                <tr><th>百科分类：</th><td><select name="category" id="select" style="width:320px;height:27px;">
                            <option value="">请选择分类</option>

                        </select></td></tr>
                <tr><th>tag标签：</th><td><input type="text" name="tag" class="textbox"><span style="padding-left: 24px;">多个标签以（,）隔开；最多支持5个标签</span></td></tr>
                <tr><th>首页大图：</th>
                    <td>
                        <input type="hidden" name="picUrl" value="" >
                        <input type="hidden" name="pic" value="" >
                        <div id="uploader-demo" style="width:320px;display:inline-block;vertical-align: middle;">
                            <!--用来存放item-->
                            <div id="fileList" class="uploader-list"></div>
                            <div id="filePicker" style="width:295px;">选择图片</div>
                        </div>
                        <span style="padding-left: 24px;">图片比例为2：1（建议大小640X320）,图片大小不得超过200KB</span>
                    </td>
                </tr>
                <tr><th>月份关联：</th><td> <input id="month" class="textbox" name="month" data-options="multiple:true,multiline:true" style="width:320px;height:80px;padding:2px" /></td></tr>
                <tr><th>概述：</th><td><textarea name="info" class="textbox" style="width:320px;height:90px"></textarea></td></tr>
                <tr><th>内容：</th>
                    <td>
                        <?php
                        echo \kucha\ueditor\UEditor::widget([
                            'model'=>$model,
                            'attribute'=>'content',
                            'clientOptions' => [
                                'initialFrameWidth'=>'100%',
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
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>
<?php $this->beginBlock('script') ?>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/webuploader/webuploader.html5only.min.js"></script>
<script>
    var Yii = {
        'index':'<?php echo \yii\helpers\Url::toRoute('wiki/index') ?>',
        'add' : '<?php echo \yii\helpers\Url::toRoute('wiki/add') ?>',
        'data': '<?php echo \yii\helpers\Url::toRoute('wiki/data') ?>',
        'rank': '<?php echo \yii\helpers\Url::toRoute('wiki/rank') ?>',
        'remove': '<?php echo \yii\helpers\Url::toRoute('wiki/remove') ?>',
        'month' : '<?php echo Url::toRoute('wiki/getmonth') ?>',
        'swf': '<?php echo Url::base(); ?>/js/webuploader/Uploader.swf'
    };
    var category = <?php echo $category ?>;
</script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/webuploader.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/wiki.js"></script>
<script>
    $('#title').blur(function(){
        var title = $(this).val();
        $.ajax({
            type : 'POST',
            url : '<?php echo Url::toRoute('wiki/valid') ?>',
            data : {
                title : title
            },
            success : function (data) {
                var obj = $.parseJSON(data);
                if (obj.status==0) {
                    $('#title-tip').html(obj.info);
                    flag = true;
                }else{
                    $('#title-tip').html('');
                     flag = false;
                }
            }
        });
    });
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
    combotree('month',true,true,true);
</script>
<?php $this->endBlock() ?>