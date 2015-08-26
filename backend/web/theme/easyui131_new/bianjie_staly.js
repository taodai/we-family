//  var easyuiPanelOnMove = function(left, top) {
//         var parentObj = $(this).panel('panel').parent();
//         if (left < 0) {
//             $(this).window('move', {
//                 left : 1
//             });
//         }
//         if (top < 0) {
//             $(this).window('move', {
//                 top : 1
//             });
//         }
//         var width = $(this).panel('options').width;
//         var height = $(this).panel('options').height;
//         var right = left + width;
//         var buttom = top + height;
//         var parentWidth = parentObj.width();
//         var parentHeight = parentObj.height();
//         if(parentObj.css("overflow")=="hidden"){
//             if(width=='auto'){      //如果宽度为auto,就给他具体值，不然右移就会超出边界                           
//                 width=parentWidth/2-50;
//                 }   
//             if(left > parentWidth-width){
//                 $(this).window('move', {
//                     "left":parentWidth-width
//                 });
//         }
//             if(top > parentHeight-$(this).parent().height()){
//                 $(this).window('move', {
//                     "top":parentHeight-$(this).parent().height()
//                 });
//         }
//         }
//     };
// $.fn.panel.defaults.onMove = easyuiPanelOnMove;
// $.fn.window.defaults.onMove = easyuiPanelOnMove;
// $.fn.dialog.defaults.onMove = easyuiPanelOnMove;

$.extend($.fn.validatebox.defaults.rules, {
    minLength : { // 判断最小长度
        validator : function(value, param) {
            return value.length >= param[0];
        },
        message : "最少输入 {0} 个字符。"
    },
    length:{validator:function(value,param){
        var len=$.trim(value).length;
            return len>=param[0]&&len<=param[1];
        },
            message:"输入内容长度必须介于{0}和{1}之间."
        },
    phone : {// 验证电话号码
        validator : function(value) {
            return /^(((\d{2,3}))|(\d{3}-))?((0\d{2,3})|0\d{2,3}-)?[1-9]\d{6,7}(-\d{1,4})?$/i.test(value);
        },
        message : "格式不正确,请使用下面格式:020-88888888"
    },
    mobile : {// 验证手机号码
        validator : function(value) {
            return /^(13|15|18)\d{9}$/i.test(value);
        },
        message : "手机号码格式不正确"
    },
    idcard : {// 验证身份证
        validator : function(value) {
            return /^\d{15}(\d{2}[A-Za-z0-9])?$/i.test(value);
        },
        message : "身份证号码格式不正确"
    },
    intOrFloat : {// 验证整数或小数
        validator : function(value) {
            return /^\d+(.\d+)?$/i.test(value);
        },
        message : "请输入数字，并确保格式正确"
    },
    currency : {// 验证货币
        validator : function(value) {
            return /^\d+(.\d+)?$/i.test(value);
        },
        message : "货币格式不正确"
    },
    qq : {// 验证QQ,从10000开始
        validator : function(value) {
            return /^[1-9]\d{4,9}$/i.test(value);
        },
        message : "QQ号码格式不正确"
    },
    integer : {// 验证整数
        validator : function(value) {
            return /^[+]?[1-9]+\d*$/i.test(value);
        },
        message : "请输入整数"
    },
    chinese : {// 验证中文
        validator : function(value) {
            return /^[\u4e00-\u9fa5]+$/i.test(value);
        },
        message : "请输入中文"
    },
    english : {// 验证英语
        validator : function(value) {
            return /^[A-Za-z]+$/i.test(value);
        },
        message : "请输入英文"
    },
    unnormal : {// 验证是否包含空格和非法字符
        validator : function(value) {
            return !/[@#\$%\^&\*]+/g.test(value);

        },
        message : "输入值不能为空和包含其他非法字符"
    },
    chinanum : {// 只能输入中文和数字
        validator : function(value) {
            return /^[\u4e00-\u9fa5_0-9]+$/.test(value);
        },
        message : "只能输入中文和数字"
    },
    
    username : {// 验证用户名
        validator : function(value) {
            return /^[a-zA-Z][a-zA-Z0-9_]{2,15}$/i.test(value);
        },
        message : "用户名不合法（字母开头，允许3-16字节，允许字母数字下划线）"
    },
    faxno : {// 验证传真
        validator : function(value) {
//            return /^[+]{0,1}(d){1,3}[ ]?([-]?((d)|[ ]){1,12})+$/i.test(value);
            return /^(((\d{2,3}))|(\d{3}-))?((0\d{2,3})|0\d{2,3}-)?[1-9]\d{6,7}(-\d{1,4})?$/i.test(value);
        },
        message : "传真号码不正确"
    },
    zip : {// 验证邮政编码
        validator : function(value) {
            return /^[1-9]\d{5}$/i.test(value);
        },
        message : "邮政编码格式不正确"
    },
    ip : {// 验证IP地址
        validator : function(value) {
            return /\d+.\d+.\d+.\d+/i.test(value);
        },
        message : "IP地址格式不正确"
    },
    name : {// 验证姓名，可以是中文或英文
            validator : function(value) {
                return /^[\u4e00-\u9fa5]+$/i.test(value) || /^\w+[\w\s]+\w+$/i.test(value);
            },
            message : "请输入姓名"
    },
    carNo:{
        validator : function(value){
            return /^[\u4E00-\u9FA5][da-zA-Z]{6}$/.test(value);
        },
        message : "车牌号码无效（例：粤J12350）"
    },
    carenergin:{
        validator : function(value){
            return /^[a-zA-Z0-9]{16}$/.test(value);
        },
        message : "发动机型号无效(例：FG6H012345654584)"
    },
    email:{
        validator : function(value){
        return /^\w+([-+.]\w+)*@\w+([-.]\w+)*.\w+([-.]\w+)*$/.test(value);
    },
    message : "请输入有效的电子邮件账号(例：abc@126.com)"  
    },
    msn:{
        validator : function(value){
        return /^\w+([-+.]\w+)*@\w+([-.]\w+)*.\w+([-.]\w+)*$/.test(value);
    },
    message : "请输入有效的msn账号(例：abc@hotnail(msn/live).com)"
    },
    same:{
        validator : function(value, param){
            if($("#"+param[0]).val() != "" && value != ""){
                return $("#"+param[0]).val() == value;
            }else{
                return true;
            }
        },
        message : "两次输入的密码不一致！"  
    }
});


// $(document).keyup(function(e){
//     var code = e.keyCode ? e.keyCode : e.which;
//     if(code == 27 || code == 96) $(".easyui-dialog").dialog('close');
//   });