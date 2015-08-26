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


<?php $this->beginPage() ?>
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
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/ajaxfileupload.js"></script>
<script>
        var upload = '<?php echo Url::toRoute('handle/uploadfile')?>';
</script>
</head>
<?php $this->beginBody() ?>
<div  class="easyui-panel" title="修改图文">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="title">标题：</label></dt>
                <dd>
                    <input id="title"  name="title" value="<?php echo $news->title; ?>" class="textbox" data-options="multiple:true,multiline:true" style="width:200px;" />
                </dd>
                <dt><label for="picUrl">配图：</label></dt>
                <dd>
                    <input type="hidden" id="picUrl" name="picUrl" >
                    <input type="hidden" id="picName" name="picName" >
                    <input type="hidden" id="content" name="content" value="<?php  ?>">
                    <input type="file" id="thumb" name="thumb" style="display:none;" onchange="changeimg()">
                    <img  width="360" height="200"  id="pic" />
                    <!-- <div id="filePicker" style="width:295px;">选择图片</div> -->
                    <a id="upimg" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">选择图片</a>
                </dd>
                <dt><label for="description">描述：</label></dt>
                <dd>
                    <textarea id="description" name="description" class="easyui-validatebox input_text" style="width:500px;height:100px" editable="false"  ></textarea>
                </dd>
                <dt><label for="content">内容：</label></dt>
                <dd>
                    <?php
                        echo \kucha\ueditor\UEditor::widget([
                            'model'=>$news,
                            'attribute'=>'content',
                            'clientOptions' => [
                                'initialContent'=>"",
                                'enableAutoSave'=>false,
                                'autoHeightEnabled'=>false,
                                //编辑区域大小
                                'initialFrameWidth' => '100%',
                                'initialFrameHeight' => '700',
                                //设置语言
                                'lang' =>'zh-cn', //中文为 zh-cn
                            ]]);
                        ?>
                    
                </dd>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
            </dl>
        </form>
    </div>
    <script type="text/javascript">
    $(function(){
        $("#upimg").click(function(){
            $("#thumb").click();
        });
    });
    function changeimg() {  
        $.ajaxFileUpload({  
            url: upload,  
            secureuri: false,  
            fileElementId: 'thumb',  
            dataType: 'json',  
            beforeSend: function() {  
                //$("#loading").show();  
            },  
            complete: function() {  
                //$("#loading").hide();  
            },  
            success: function(data) {  
                if(data.url!=""){
                    $("#pic").attr('src',data.url);
                    $("#picUrl").val(data.url);
                    $("#picName").val(data.pic);
                }                 
            },  
            error: function(data, status, e) {  
                alert(e);  
            }  
        });
        return false;
    } 
    function save()
    {
        var content = UE.getEditor('weixinnews-content').getContent();
        $("#content").val(content);
        var url = "/weixin/handle/news-add";
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
                    $.messager.show({  
                        title: '提示',  
                        msg: '保存成功'  
                    }); 
                }  
            }  
        });  
    }
    </script>

    <?php $this->endBody() ?>
    <?php $this->endPage() ?>