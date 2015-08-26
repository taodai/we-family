<?php
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<table id="datagrid"></table>


<div id="datagrid_tool" style="padding:5px;">
    <div style="margin-bottom:5px;">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="datagrid_tool.add();">添加</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="datagrid_tool.edit();">修改</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="datagrid_tool.remove();">删除</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="datagrid_tool.reload();">刷新</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="datagrid_tool.redo();">取消选择</a>
    </div>
</div>
<form id="dlg_add" style="margin:0;padding:10px 0 10px 0px;color:#333;">
    <input type="hidden" name="cnid" id="cnid" value="" >
    <table class="dlg_table">
        <tr><th width="100">课程主题：</th><td><input type="text" name="theme" class="textbox" style="width:400px;"></td></tr>
        <tr><th>主题图片：</th>
            <td>
                <input type="hidden" id="theme_pic" name="theme_pic" value="" >
                <div id="uploader-demo" class="easyui-tooltip" title="图片比例为2：1（建议尺寸：640像素 * 320像素）"  style="width:400px;display:inline-block;vertical-align: middle;">
                    <!--用来存放item-->
                    <div id="fileList" class="uploader-list">
                        <span class="delete">X</span>
                        <div class="file-item thumbnail upload-state-done">
                            <img src="" width="160" height="80" id="pic_name" />
                        </div>
                    </div>
                    <div id="filePicker" style="width: 370px;">选择图片</div>
                </div>
<!--                <div style="text-align: center">图片建议尺寸：900像素 * 500像素</div>-->
            </td>
        </tr>
        <tr><th>分享讲师：</th><td><select id="lecturer" name="share_lecturer">
                                    <option value="">请选择讲师</option>
                                </select>
            </td>
        </tr>
        <tr><th>分享概述：</th><td><input type="text" class="easyui-textbox" name="share_desc" data-options="multiline:true" style="width:406px;height:60px"><input type="checkbox" id="confirm" name="confirm" checked="checked" >(自动从内容截取)</tr>
        <tr><th>分享内容：</th>
            <td>
                <?php
                echo \kucha\ueditor\UEditor::widget([
                    'model'=>$model_course,
                    'attribute'=>'share_content',
                    'clientOptions' => [
                        'toolbars'=>[['source', 'undo', 'redo', '|', 'fontsize', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', '|', 'lineheight', '|', 'indent', '|',
                            'wordimage','|','imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                            'simpleupload'
                        ]],
                        'textarea'=>'share_content',
                        'initialFrameWidth'=>'100%',
                        'initialFrameHeight'=>200,
                        'enableAutoSave'=>false,
                        'autoHeightEnabled'=>false,
                        'zIndex'=>9999,
                        //设置语言
                        'lang' =>'zh-cn', //中文为 zh-cn
                    ]]);
                ?>
            </td>
        </tr>
        <tr><th>分享时间：</th><td><input type="text" name="share_time" class="easyui-datetimebox textbox" data-options="required:true" style="width:406px;height:26px;"></td></tr>
        <tr><th>分享群二维码：</th>
            <td>
                <input type="hidden" class="textbox" id="share_qrcode" name="share_qrcode" value="" >
                <div id="uploader-demo2" style="width:400px;display:inline-block;vertical-align: middle;">
                    <!--用来存放item-->
                    <div id="fileList2" class="uploader-list">
                        <span class="delete">X</span>
                        <div class="file-item thumbnail upload-state-done">
                            <img src="" width="80" height="80"  />
                        </div>
                    </div>
                    <div id="filePicker2" style="width: 370px;">选择图片</div>
                </div>
            </td>
        </tr>
    </table>
</form>
    <form id="dlg_show" style="margin:0;padding:10px 10px 10px 5px;color:#333;">
        <input type="hidden" name="themeid" id="themeid" value="" >
        <input type="hidden" name="id" id="crid" value="" >
        <table class="dlg_table">
            <tr><th width="100">回顾主题：</th><td><input type="text" id="theme" readonly="readonly" value="" class="textbox" style="width:400px;"></td></tr>
            <tr><th>回顾内容：</th>
                <td>
                    <?php
                    echo \kucha\ueditor\UEditor::widget([
                        'model'=>$model,
                        'attribute'=>'content',
                        'clientOptions' => [
                            'textarea'=>'content',
                            'initialFrameWidth'=>'100%',
                            'enableAutoSave'=>false,
                            'autoHeightEnabled'=>false,
                            'zIndex'=>9999,
                            //编辑区域大小
                            'initialFrameHeight' => '240',
                            //设置语言
                            'lang' =>'zh-cn', //中文为 zh-cn
                        ]]);
                    ?>
                </td>
            </tr>
        </table>
    </form>
    <div id="mm" class="easyui-menu" style="width:120px;">
        <div onclick="datagrid_tool.sub_add()" data-options="iconCls:'icon-add'">课堂回顾</div>
    </div>

    <div id="review">
        <div id="review_content">

        </div>
    </div>
<?php $this->beginBlock('script') ?>
<script>
    $('.easyui-tooltip').tooltip({
        position: 'right'
    });
    var Yii = {
        'add' : '<?php echo Url::toRoute('course-notice/add') ?>',
        'edit' : '<?php echo Url::toRoute('course-notice/dit') ?>',
        'data': '<?php echo Url::toRoute('course-notice/data') ?>',
        'one' : '<?php echo Url::toRoute('course-notice/one') ?>',
        'remove': '<?php echo Url::toRoute('course-notice/remove') ?>',
        'review': '<?php echo Url::toRoute('course-notice/review') ?>',
        'lecturer' : '<?php echo Url::toRoute('course-notice/lecturer') ?>'
    };
    $('#lecturer').combobox({
        required: true,
        missingMessage: '请选择讲师',
        url : Yii.lecturer,
        valueField : 'id',
        lines : true,
        multiple : false,
        editable : false
    });
    // 初始化Web Uploader
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,
        // swf文件路径
        swf: '<?php echo Url::base(); ?>/js/webuploader/Uploader.swf',
        // 文件接收服务端。
        server: upload,
        fileVal:'UploadForm[file]',
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id:'#filePicker',
            multiple:false
        },
        thumb: {
            quality:100
        },
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });
    upload_fun(uploader,'fileList',170,80,'theme_pic');
    // 初始化Web Uploader
    var uploader2 = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,
        // swf文件路径
        swf: '<?php echo Url::base(); ?>/js/webuploader/Uploader.swf',
        // 文件接收服务端。
        server: upload,
        fileVal:'UploadForm[file]',
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id:'#filePicker2',
            multiple:false
        },
        thumb: {
            quality:100
        },
        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });
    upload_fun(uploader2,'fileList2',80,80,'share_qrcode');
    function upload_fun(uploader,id,width,height,picid){
        // 当有文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            var $li = $(
                    '<span class="delete">X</span>'+
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '<div class="info">' + file.name + '</div>' +
                    '</div>'
                ),
                $img = $li.find('img');
            var $id = $( '#'+file.id );


            // $list为容器jQuery实例
            $('#'+id).html( $li );

            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr( 'src', src );
            }, width, height );

        });
        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress span');

            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="progress"><span></span></p>').appendTo( $li).find('span');
            }

            $percent.css( 'width', percentage * 100 + '%' );
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function(file,data) {
            $( '#'+file.id ).addClass('upload-state-done');
            if(data.status==1){
                $('#'+picid).val(data.url);
                $('<div class="success"></div>').appendTo($( '#'+file.id )).text(data.info).slideDown();
                setTimeout(function(){
                    $('.success').slideUp(500);
                },2000)
            }else{
                $('<div class="error"></div>').appendTo($( '#'+file.id )).text(data.info).slideDown();
            }
        });

        // 文件上传失败，显示上传出错。
        uploader.on( 'uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.error');

            // 避免重复创建
            if ( !$error.length ) {
                $error = $('<div class="error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.progress').remove();
        });
    }
</script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/course.js"></script>
<?php $this->endBlock() ?>