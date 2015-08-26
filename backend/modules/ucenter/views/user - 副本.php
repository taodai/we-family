<?php
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/easyui131/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/easyui131/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo Url::base(); ?>/theme/system/helper/style.css">
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>

    <table id="dg" pageSize="20" toolbar="#toolbar" pagination="true"  rownumbers="false" fitColumns="true" singleSelect="true" fit="true" 
    >  
        <thead>  
            <tr>
                <th field="uid" width="10" sortable="true" >编号</th>
                <th field="uname" width="50" sortable="true">会员登录账号</th>
                <th field="studyid" width="50" sortable="true">用户学号</th>
                <th field="real_name" width="20">真实姓名</th>
                <th field="babyName" width="50">昵称</th>
                <th field="gender" width="20" formatter="format_male">性别</th>
                <th field="email" width="50">邮箱</th>
                <th field="is_author" width="20" formatter="format_author">微信绑定</th>
                <th field="status" width="20" formatter="format_status">状态</th>
                <th field="regtime" width="50" >注册时间</th>
                <th field="last_login" width="50" >最后登录时间</th>
<!--                 <th field="loginip" width="50" >登录IP</th>
                <th field="logintimes" width="50" >登录次数</th> -->
            </tr>  
        </thead>  
    </table>     
    <div id="toolbar" style="height:auto; padding:5px 12px;">
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">添加</a>   -->
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">修改</a>   -->
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="crowd()">入群标注</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy()">停用</a>
        <form id="search_form">
            <label>会员登录账号：<input type="text" class="input_text" name="uname" /></label>     
            &nbsp;&nbsp;<label>用户学号：<input type="text" class="input_text" name="studyid" /></label>     
            <!-- &nbsp;&nbsp;<label>昵称：<input type="text" class="input_text" name="babyName" /></label>      -->
<!--             &nbsp;&nbsp;<label>入职日期：<input class="easyui-datebox" style="width:100px" name="st_entrydate[]" /> - <input class="easyui-datebox" style="width:100px" name="st_entrydate[]" /></label> 
            &nbsp;&nbsp;<label>是否在职：<select name="st_status" class="easyui-combobox" editable="false">
                        <option value="0" selected="selected" >全部</option>
                        <option value="1" >在职</option>
                        <option value="2">离职</option></select></label>
            <br/> -->
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()">查询</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="searchReset()">重置</a>
        </form>  
    </div>  
     
    <div id="dlg" class="easyui-dialog singleColumn" 
            closed="true">
        <form id="fm" method="post" novalidate>
            <dl>
                <dt><label for="user_type">会员类型：</label></dt>
                <dd>
                    <input type="text" id="user_type" name="user_type"  required="true" class="easyui-combobox" />
                </dd>
                <dt><label for="areaid">所在地区：</label></dt>
                <dd>
                    <input type="text" id="areaid" name="areaid" class="easyui-combobox" required="false"/>
                </dd>
                <dt><label for="username">登录名：</label></dt>
                <dd>
                    <input type="text" id="username" name="username"  editable="false" class="easyui-validatebox input_text" required="true" validType="length[1,100]"  />
                </dd>
                <dt><label for="truename">真实姓名：</label></dt>
                <dd>
                    <input type="text" id="truename" name="truename"  editable="false" class="easyui-validatebox input_text" required="true" validType="length[1,100]"  />
                </dd>
                <dt><label for="mobile">手机号码：</label></dt>
                <dd>
                    <input id="mobile" name="mobile" class="easyui-validatebox input_text" editable="false" required="true" validType="mobile">
                </dd>
                <dt><label for="email">邮箱：</label></dt>
                <dd>
                    <input id="email" name="email" class="easyui-validatebox input_text" editable="false">
                </dd>
                <dt><label for="gender">性别：</label>
                <dd><select id="gender" name="gender" class="easyui-combobox" editable="false">
                        <option value="1" selected="true">男</option>
                        <option value="2">女</option>
                    </select>
                </dd>
                <dt><label for="status">状态：</label>
                <dd><select id="status" name="status" class="easyui-combobox" editable="false">
                        <option value="1">正常</option>
                        <option value="2">停用</option>
                    </select>
                </dd>  
                <dt></dt>
                <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save()">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
                </dd>
            </dl>      
        </form>
    </div> 
<script>
    var url;
    $(function(){
        $('#dg').datagrid({
            url:'/ucenter/user/user-list',
            // onDblClickRow:function(rowIndex,rowData){
            //     edit();
            // }
            // rowStyler: function(index,row){
            //     if (index%2 == 1){
            //         return 'background-color:#DDDDDD;color:#fff;';
            //         }
            // }
        });
        // $('#user_type').combobox({
        //     url:'/ucenter/type/get-type',
        //     valueField:'mt_id',
        //     textField:'mt_name'
        // });
        // $('#areaid').combobox({
        //     url:'/ucenter/user/get-area',
        //     valueField:'id',
        //     textField:'name'
        // });
    });

    function format_mobile(val,row)
    {
        if(val){
            if(row.vmobile == 1){
                return val+'<span style="color:green;">[已认证]</span>';
            }else{
                return val+'<span style="color:red;">[未认证]</span>';
            }
        }
    } 

    function format_author(val,row)
    {
        if(val){
            if(row.is_author == 1){
                return '<span style="color:green;">已绑定微信</span>';
            }else{
                return '<span style="color:red;">未绑定微信</span>';
            }
        }
    } 


    function format_male(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">男</span>';
        } else {
        return '<span style="color:red;">女</span>';
        }
    } 

    function format_status(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">正常</span>';
        } else {
        return '<span style="color:red;">停用</span>';
        }
    } 


    function formClear()
    {
        var formObject = $('#fm');
        formObject.form('clear');  //清空
        $(".validatebox-invalid").blur();
        formObject.find("input").removeClass("validatebox-invalid");
    }

    
    function add()
    {
        url = "/ucenter/user/user-add";
        formClear();
        $('#dlg').dialog('open').dialog('setTitle','新建'); 
        // $('#sex').combobox('select',1);
        // $('#status').combobox('select',1);
    }
    function edit()
    {
        formClear();
        var row = $('#dg').datagrid('getSelected');  
        if (row){  
            $('#dlg').dialog('open').dialog('setTitle','编辑');  
            // 加载数值
            $('#fm').form('load',row);  
            url = "/ucenter/user/user-edit?userid="+row.userid;
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条记录'  
            });  
        }
    }

    function crowd() 
    {
        $('.pagination').pagination('select');
    }

    function doSearch(){  
        $('#dg').datagrid('load', {
        uname: $("#search_form input[name='uname']").val(),
        studyid: $("#search_form input[name='studyid']").val()
        });  
        
    }
    function searchReset(){
        
        $('#dg').datagrid('load', {});
        $('#search_form').form('clear');
    }
    function destroy()
    {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('确认','确定停用用户：' + row.username + '?',function(r){  
                if (r){  
                    $.post(
                        '<?php echo Url::home(); ?>/ucenter/user/user-del'
                        ,{userid:row.userid},function(result){  
                        if (result.suc){  
                            $('#dg').datagrid('reload');    // reload the user data  
                        } else {  
                            $.messager.show({   // show error message  
                                title: '错误提示',  
                                msg: result.errorMsg  
                            });  
                        }  
                    },'json');  
                }  
            });  
        }else{
            $.messager.show({  
                title: '错误提示',  
                msg: '请先选择一条记录'  
            });  
        }
    }

    // function save()
    // {
    //     $('#fm').form('submit',
    //         {  
    //         url: url,  
    //         success: function(result){  
    //             var result = eval('('+result+')');  
    //             if (result.errorMsg){  
    //                 $.messager.show({  
    //                     title: '错误提示',  
    //                     msg: result.errorMsg  
    //                 });  
    //             } else {  
    //                 $('#dlg').dialog('close');      // close the dialog  
    //                 $('#dg').datagrid('reload');    // reload the user data  
    //             }  
    //         }  
    //     });  
    // }
</script> 
</body>
</html>