// 初始化Web Uploader
var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    swf:Yii.swf,
    // 文件接收服务端。
    server: upload,
    fileVal:'UploadForm[file]',
    formData:{
        thumb:'wiki'
    },
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: {
    id:'#filePicker',
        multiple:false
},
    thumb: {
        quality:100
    },
    fileSingleSizeLimit:2*1024*100,
    fileSizeLimit:2*1024*100,
    //compress:{
    //    width: 640,
    //    height: 320,
    //    crop: true
    //},
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
    }, 312, 152 );

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
uploader.on( 'uploadSuccess', function(file,data) {;
    $( '#'+file.id ).addClass('upload-state-done');
    if(data.status==1){
        $('input[name="picUrl"]').val(data.url);
        $('input[name="pic"]').val(data.pic);
        $('<div class="success"></div>').appendTo($( '#'+file.id )).text(data.info).slideDown();
        setTimeout(function(){
            $('.success').slideUp(500);
        },2000)
    }else{
        $('<div class="error"></div>').appendTo($( '#'+file.id )).text(data.info).slideDown();
    }
});
uploader.on('error', function(file,data){
    $.messager.alert('警告操作！', '文件大小超出限制！', 'warning');
});
// 文件上传失败，显示上传出错。
uploader.on( 'uploadError', function( file ) {
    var $li = $( '#'+file.id ),
        $error = $li.find('div.error');
    // 避免重复创建
    if ( !$error.length ) {
        $error = $('<div class="error"></div>').appendTo( $li );
    }

    $error.text('上传失败').slideDown();
});

// 完成上传完了，成功或者失败，先删除进度条。
uploader.on( 'uploadComplete', function( file ) {
    $( '#'+file.id ).find('.progress').remove();
});
