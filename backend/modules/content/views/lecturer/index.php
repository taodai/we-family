<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<table id="lecturer"></table>

<div id="lecturer_tool" style="padding:5px;">
    <div style="margin-bottom:5px;">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="lecturer_tool.add();">添加</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="lecturer_tool.edit();">修改</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="lecturer_tool.remove();">删除</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true"  onclick="lecturer_tool.reload();">刷新</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="lecturer_tool.redo();">取消选择</a>
    </div>
<!--    <div style="padding:0 0 0 7px;color:#333;">-->
<!--        讲师姓名：<input type="text" class="textbox" name="user" style="width:110px">-->
<!--        创建时间从：<input type="text" name="date_from" class="easyui-datebox" editable="false" style="width:110px">-->
<!--        到：<input type="text" name="date_to" class="easyui-datebox" editable="false" style="width:110px">-->
<!--        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="obj.search();">查询</a>-->
<!--    </div>-->
</div>


<form id="lecturer_add" style="margin:0;padding:5px 0 0 0px;color:#333;">
    <input type="hidden" name="leid" id="leid" value="">
    <table class="dlg_table">
        <tr><th width="90">讲师姓名：</th><td><input type="text" name="le_name" class="textbox" style="width:400px;"></td></tr>
        <tr><th width="90">讲师地区：</th><td><input type="text" name="le_area" class="textbox" style="width:400px;"></td></tr>
        <tr><th>讲师照片：</th>
            <td>
                <input type="hidden" name="le_pic" value="" >
                <div id="uploader-demo" style="width:400px;display:inline-block;vertical-align: middle;">
                    <!--用来存放item-->
                    <div id="fileList" class="uploader-list">
                        <div class="file-item thumbnail upload-state-done">
                            <img src="" width="70" height="90" id="pic_name" />
                        </div>
                    </div>
                    <div id="filePicker" style="width: 370px;">选择图片</div>
                </div>
            </td>
        </tr>
        <tr>
            <th>讲师头衔：</th><td><input class="easyui-textbox" name="le_title" data-options="multiline:true" style="width:406px;height:60px"></td>
        </tr>
        <tr>
            <th>讲师介绍：</th>
            <td>
                <?php
                echo \kucha\ueditor\UEditor::widget([
                    'model'=>$model,
                    'attribute'=>'le_desc',
                    'clientOptions' => [
                        'toolbars'=>[['source', 'undo', 'redo', '|', 'fontsize', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', '|', 'lineheight', '|', 'indent', '|',
                            'wordimage','|','imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload'
                        ]],
                        'textarea'=>'le_desc',
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
        <tr><th>讲师排名：</th><td><input type="text" name="le_weight" class="textbox" value="0" style="width:400px"></td></tr>
    </table>
</form>

<?php $this->beginBlock('script') ?>
<script>
    var Yii = {
        'add' : '<?php echo \yii\helpers\Url::toRoute('lecturer/add') ?>',
        'edit' : '<?php echo \yii\helpers\Url::toRoute('lecturer/edit') ?>',
        'data': '<?php echo \yii\helpers\Url::toRoute('lecturer/data') ?>',
        'remove': '<?php echo \yii\helpers\Url::toRoute('lecturer/remove') ?>'
    };
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
        // 内部根据当前运行是创建，可能是input元素，也可能是flash。
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
    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                '<img>' +
                '<div class="info">' + file.name + '</div>' +
                '</div>'
            ),
            $img = $li.find('img');
        var $id = $( '#'+file.id );

        // $list为容器jQuery实例
        $('#fileList').html( $li );

        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, 70, 90 );

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
            $('input[name="le_pic"]').val(data.url);
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

</script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/js/lecturer.js"></script>
<?php $this->endBlock() ?>