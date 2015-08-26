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
    <link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/js/webuploader/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/css/common.css">
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/bianjie_staly.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/dlg_helper.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/js/webuploader/webuploader.html5only.min.js"></script>
    <script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/widget.js"></script>
    <style>
        .webuploader-pick{width:auto;}
        .del_attr{cursor: pointer;}
    </style>
</head>
<body>
<?php $this->beginBody() ?>

    <form id="pro_form" action="<?php echo Url::toRoute('products/add') ?>" method="post" style="margin:0;color:#333;">
        <input type="hidden" name="pro_id" value="<?php echo $product['pro_id'] ?>">
        <div id="tt" class="easyui-tabs">
            <div title="基本信息" class="table-responsive">
                <table class="m-table" style="width: 100%">
                    <tr><th width="100">商品名称：</th>
                        <td><input type="text" id="title" name="pro_title" class="textbox easyui-validatebox" data-options="required:true" value="<?php echo $product['pro_title']?>"></td>
                    </tr>
                    <tr><th>商品货号：</th>
                        <td><input type="text" id="pro_sn" name="pro_sn" class="textbox easyui-validatebox"
                                   data-options="required:true" validType="unchs" value="<?php echo $product['pro_sn']?>"></td>
                    </tr>
                    <tr><th>商品价格：</th>
                        <td><input type="text" id="pro_price" name="pro_price" class="textbox easyui-validatebox"
                                   value="<?php echo $product['pro_price']?>"></td>
                    </tr>
                    <tr><th>所属分类：</th><td><select name="pro_cat" id="select" style="width:320px;height:27px;">
                                <option value="">请选择分类</option>
                            </select></td></tr>
                    <tr style="display: none;"><th>商品品牌：</th><td><select name="pro_brand" id="brand" style="width:320px;height:27px;">
                                <option value="">请选择分类</option>
                            </select></td></tr>
                    <tr><th>商品相册：</th>
                        <td>
                            <input type="hidden" id="default" name="pro_image_default" value="<?php echo $product['pro_image_default']?>">
                            <input type="hidden" id="prefix" name="pro_prefix" value="<?php echo $product['pro_prefix']?>">
                           <div id="pro_image_file">
                               <?php
                               if($product['pro_image_file']) {
                                   foreach ($product['pro_image_file'] as $key => $value) {
                                       ?>
                                       <input type="hidden" class="input-img" value="<?php echo $value ?>" name="pro_image_file[]">
                                   <?php }
                               }
                                ?>
                           </div>
                            <div id="wrapper">
                                <div id="container">
                                    <div id="uploader">
                                        <div class="queueList">
                                            <div id="dndArea" class="placeholder element-invisible">
                                                <div id="filePicker"></div>
                                                <p>点击按钮上传图片，按住Ctrl可多选，最多5张，单张图片不得大于200kb</p>
                                            </div>
                                            <ul class="filelist">
                                                <?php if($product['pro_image_file']){
                                                    foreach($product['pro_image_file'] as $key=>$value) {
                                                        ?>
                                                        <li class="state-complete state-edit">
                                                            <p class="title"></p>
                                                            <p class="imgWrap">
                                                                <img
                                                                    src="<?php echo '/'.$product['pro_prefix'].$value ?>">
                                                            </p>
                                                            <p class="progress">
                                                                <span></span>
                                                            </p>
                                                            <span class="successfull <?php if($product['pro_image_default']==$value) echo 'default' ?> "></span>

                                                            <div class="file-panel" style="height: 0px;">
                                                                <span class="del">删除</span>
                                                                <span class="rotateRight reupload" title="点击设置主图">设置主图</span>
                                                            </div>
                                                        </li>
                                                    <?php
                                                    }
                                                } ?>
                                            </ul>
                                        </div>
                                        <div class="statusBar" style="display:block;">
                                            <div class="progress">
                                                <span class="text">0%</span>
                                                <span class="percentage"></span>
                                            </div><div class="info">共0张（0B），已上传0张 ,请点击图片设置主图</div>
                                            <div class="btns">
                                                <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>是否上架</th>
                        <td>
                            <label class="form-label"><input type="radio" name="pro_status" <?php if($product['pro_status']==1) echo 'checked="checked"'?> value="1">上架</label>
                            <label class="form-label"><input type="radio" name="pro_status" <?php if($product['pro_status']==2) echo 'checked="checked"'?> value="2">下架</label>
                        </td>
                    </tr>
                    <tr>
                        <th>是否包邮</th>
                        <td>
                            <label class="form-label"><input type="radio" name="pro_freight" <?php if($product['pro_freight']==1) echo 'checked="checked"'?> value="1">是</label>
                            <label class="form-label"><input type="radio" name="pro_freight" <?php if($product['pro_freight']==2) echo 'checked="checked"'?> value="2">否</label>
                        </td>
                    </tr>
                    <tr>
                        <th>优惠券</th>
                        <td>
                            <label class="form-label"><input type="radio" name="pro_coupon" <?php if($product['pro_coupon']==1) echo 'checked="checked"'?> value="1">可用</label>
                            <label class="form-label"><input type="radio" name="pro_coupon" <?php if($product['pro_coupon']==0) echo 'checked="checked"'?> value="0">不可用</label>
                        </td>
                    </tr>
                    <tr>
                        <th>产品库存</th>
                        <td>
                            <input type="text" class="textbox easyui-numberbox" min="0"  max="100000000" name="pro_store" value="<?php echo $product['pro_store']?>" style="height:27px">
                        </td>
                    </tr>
                    <tr>
                        <th>商品销售量</th>
                        <td>
                            <input type="text" class="textbox easyui-numberbox" min="0"  max="10000000" name="pro_sales" value="<?php echo $product['pro_sales'] ?>" style="height:27px">
                        </td>
                    </tr>
                </table>
            </div>
            <div title="商品规格" class="table-responsive">
                <table class="m-table" style="width: 100%">
                    <tr><th width="100">商品属性：</th>
                        <td>
                            <p>(多个值请用"|"隔开)</p>
                            <dl class="pro-attr">
                                <?php if($product['attr']){
                                    foreach($product['attr'] as $key=>$value) {
                                        ?>
                                        <dd>属性名<input type="text" name="pp_name[<?php echo $value['pp_id'] ?>]" value="<?php echo $value['pp_name'] ?>" class="textbox">
                                            属性值<input type="text" name="pp_value[<?php echo $value['pp_id'] ?>]" value="<?php echo $value['pp_value'] ?>" class="textbox"> <span data="<?php echo $value['pp_id'] ?>" onclick="del_attr(this)" class="del_attr">X</span></dd>
                                    <?php
                                    }
                                }else{
                                ?>
                                <dd>属性名<input type="text" name="pp_name[0][]" class="textbox"> 属性值<input type="text" name="pp_value[0][]" class="textbox">
                                <?php } ?>
                            </dl>
                            <div><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" id="attr-add">新增自定义</a></div>
                        </td>
                    </tr>
                    <tr><th width="100">商品标签：</th>
                        <td>
                            <input id="pro_tag" class="textbox" name="pro_tag[]" style="width:320px;">
                        </td>
                    </tr>
                </table>
            </div>
            <div title="商品详情" class="table-responsive">
                <table class="m-table" style="width: 100%">
                    <tr><th width="100">商品描述：</th>
                        <td>
                            <?php
                            echo \kucha\ueditor\UEditor::widget([
                                'model'=>$model,
                                'attribute'=>'pro_desc',
                                'clientOptions' => [
                                    'textarea'=>'pro_desc',
                                    'initialContent'=>$product['pro_desc'],
                                    'initialFrameWidth'=>'100%',
                                    'enableAutoSave'=>false,
                                    'autoHeightEnabled'=>false,
                                    //编辑区域大小
                                    'initialFrameHeight' => '300',
                                    //设置语言
                                    'lang' =>'zh-cn', //中文为 zh-cn
                                ]]);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="table-responsive">
            <table class="m-table">
                <tr><td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" id="st">保存</a></td></tr>
            </table>
        </div>
    </form>
<script>
    var Yii = {
        'index':'<?php echo \yii\helpers\Url::toRoute('products/index') ?>',
        'add' : '<?php echo \yii\helpers\Url::toRoute('products/add') ?>',
        'data': '<?php echo \yii\helpers\Url::toRoute('products/data') ?>',
        'rank': '<?php echo \yii\helpers\Url::toRoute('products/rank') ?>',
        'remove': '<?php echo \yii\helpers\Url::toRoute('products/remove') ?>',
        'del_attr': '<?php echo \yii\helpers\Url::toRoute('products/del') ?>',
        'uploadfile' : '<?php echo Url::toRoute(['/content/lecturer/uploadfile'])?>',
        'swf': '<?php echo Url::base(); ?>/js/webuploader/Uploader.swf'
    };
    var filenum = <?php echo count($product['pro_image_file']); ?>;
    var act = '<?php echo $product['pro_prefix']?>';
    var pro_cate = <?php echo $pro_cate ?>;
    var pro_brand = <?php echo $pro_brand ?>;
    var pro_tag = <?php echo $pro_tag ?>;
</script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/webuploader/upload.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/product.js"></script>
<script type="text/javascript">
    $(function(){
        $('#select').combotree('setValue','<?php echo $product['pro_cat']?>');
        //$('#brand').combobox('setValue','<?php echo $product['pro_brand']?>');
        $('#pro_tag').combotree('setValues',[<?php echo $product['pro_tag']?>]);
    });

    function del_attr(obj){
        $.ajax({
            type : 'POST',
            url : Yii.del_attr,
            data : {
                id : $(obj).attr('data')
            },
            beforeSend : function () {

            },
            success : function (data) {
                if (data==1) {
                    $(obj).parent().remove();
                }
            }
        });
    }
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>