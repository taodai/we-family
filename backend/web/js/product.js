/**
 * Created by xiaodongning on 2015/7/7.
 */

//提交表单

$('#st').click(function(){
    if ($('#pro_form').form('validate')) {
        $('#pro_form').submit();
    }
});

//ajax提交表单
$('#pro_form').form({
    onSubmit: function(param){
        if($('#default').val()==''){
            $.messager.alert('提示！', '请至少上传一张图片并设置为主图', 'warning');
            return false;
        }
    },
    success:function(data){
        if(data>0){
            $.messager.alert('提示', '保存成功', 'info',function(){
                $('#pro_form').form('reset');
                UE.getEditor('products-pro_desc').execCommand('cleardoc');
                parent.$('#maintab').tabs('select', '商品管理');
                //此处为子菜单id iframe的name值
                parent.frames['icon-product-53'].$('#pro_dg').datagrid('reload');


            });

        }else{
            $.messager.alert('提示', data, 'error');
        }
    }
});

//验证
$.extend($.fn.validatebox.defaults.rules, {
    unchs : {
        validator : function (value) {
            return !(/.*[\u4e00-\u9fa5]+.*$/.test(value));
        },
        message : '不能含中文'
    },
    isnumber : {
        validator : function (value){
            return !isNaN(value);
        },
        message : '必须是数字'
    }
});
//点击设置默认图片
$(document).on('click','.rotateRight',function(){
    var index = $(this).index('.rotateRight');
    var img = $('.input-img').eq(index).val();
    //var id = $(this).attr('id');
    //var img = $('input[data="'+id+'"]').val();
    $('#default').val(img);
    $('.state-complete').find('.successfull').removeClass('default');
    $(this).parent().siblings('.successfull').addClass('default');

});

$('#attr-add').click(function(){
    var dd = '<dd>属性名<input type="text" name="pp_name[0][]" class="textbox"> 属性值<input type="text" name="pp_value[0][]" class="textbox"></dd>';
    $('.pro-attr').append(dd);
});

$('#select').combotree({
    required:true,
    lines : true,
    data   : pro_cate,
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



$('#pro_tag').combotree({
    lines : true,
    data   : pro_tag,
    multiple:true,
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
//$('#brand').combobox({
//    required:true,
//    data   : pro_brand,
//    valueField:'id',
//    textField:'text'
//});