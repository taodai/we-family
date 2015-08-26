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
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/bianjie_staly.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/easyui131/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Url::base(); ?>/theme/system/helper/widget.js"></script> 
</head>
<body class="easyui-layout" style="height:auto;">
    <div data-options="region:'west',split:true" title="模块" style="width:30%;height:500px;">
        <table id="dg" pageSize="20" toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true" fit="true" >  
            <thead>  
                <tr>
                    <th field="moid" width="50" sortable="true">编号</th>
                    <th field="mo_title" width="80">模块名称</th>
                    <th field="mo_weight" width="50">显示顺序</th>
                    <th field="mo_status" width="50" formatter="format_status">状态</th>
                    <th field="mo_tag" width="80">模块标签</th>
                </tr>  
            </thead>  
        </table> 
        
        <div id="toolbar" style="height:auto; padding:5px 12px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add('module')">添加</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit('module')">修改</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy('module')">停用</a>
        </div>
        <div id="dlg" class="easyui-dialog singleColumn" 
                closed="true">
            <form id="fm" method="post" novalidate>
                <dl>
                    <dt><label for="mo_title">模块名称：</label></dt>
                    <dd>
                        <input type="text" id="mo_title" name="mo_title" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                    </dd>
                    <dt><label for="mo_tag">模块标签：</label></dt>
                    <dd>
                        <input type="text" id="mo_tag" name="mo_tag" style="width:200px;" editable="false" class="easyui-validatebox input_text" validType="length[1,100]" />
                    </dd>
                    <dt><label for="mo_weight">显示顺序：</label></dt>
                    <dd>
                        <input id="mo_weight" name="mo_weight" class="easyui-validatebox input_text" editable="false" style="width:156px;">
                    </dd>
                    <dt><label for="mo_status">状态：</label>
                    <dd><select id="mo_status" name="mo_status" class="easyui-combobox" editable="false">
                            <option value="1">正常</option>
                            <option value="2">停用</option>
                        </select>
                    </dd>  
                    <dt>　</dt>
                    <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save('module')">保存</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a></dd>
                </dl>
            </form>
        </div>
    </div>
    <div data-options="region:'center',title:'菜单'"  id="center">
        <table id="tg"
                data-options="  
                    rownumbers: false,  
                    height:'auto',
                    animate: false,  
                    collapsible: true,  
                    fitColumns: true,  
                    idField: 'sm_id',  
                    treeField: 'sm_menu_title',
                    fit:true,
                    toolbar:'#toolbar2'
                ">  
            <thead>  
                <tr>
                    <th field="sm_id" width="50" sortable="true">编号</th>
                    <th field="sm_menu_title" width="80">菜单名称</th>
                    <th field="sm_menu_url" width="120">网址</th>
                    <th field="sm_weight" width="50">显示顺序</th>
                    <th field="sm_menu_png" width="50" formatter="format_img">图标</th>
                    <th field="sm_tag" width="50" >所属模块</th>
                    <th field="sm_status" width="50" formatter="format_status">状态</th>
                </tr>  
            </thead>  
        </table> 

        <div id="toolbar2" style="height:auto; padding:5px 12px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add('menu')">添加</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit('menu')">修改</a>  
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy('menu')" id="disable">停用</a>
            <!-- <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="reloadmenu()">重载左侧导航</a> -->
        </div>
        <div id="dlg_menu" class="easyui-dialog singleColumn" 
                closed="true">
            <form id="fm_menu" method="post" novalidate>
                <dl>
                    <dt><label for="sm_menu_title">菜单名称：</label></dt>
                    <dd>
                        <input type="text" id="sm_menu_title" name="sm_menu_title" class="easyui-validatebox input_text" required="true" validType="length[1,50]" />
                    </dd>
                    <dt><label for="sm_menu_url">网址：</label></dt>
                    <dd>
                        <input type="text" id="sm_menu_url" name="sm_menu_url" style="width:200px;" editable="false" class="easyui-validatebox input_text" validType="length[1,100]" />
                    </dd>
                    <dt><label for="sm_parent_id">上级菜单：</label></dt>
                    <dd>
                        <input id="sm_parent_id" name="sm_parent_id" class="easyui-combotree" required="true" editable="false"  data-options="url:'<?php echo Url::base().'/system/menu/parent-menu';?>'"/>
                    </dd>
                    <dt><label for="sm_menu_png">图标：</label></dt>
                    <dd>
                        <input id="sm_menu_png" name="sm_menu_png" class="easyui-combobox" editable="false" style="width:156px;">
                    </dd>
                    <dt><label for="sm_weight">显示顺序：</label></dt>
                    <dd>
                        <input id="sm_weight" name="sm_weight" class="easyui-validatebox input_text" editable="false" style="width:156px;">
                    </dd>
                    <dt><label for="sm_tag">模块：</label></dt>
                    <dd>
                        <input id="sm_tag" name="sm_tag" class="easyui-validatebox input_text" disabled="true" style="width:156px;">
                    </dd>
                    <dt><label for="sm_status">状态：</label>
                    <dd><select id="sm_status" name="sm_status" class="easyui-combobox" editable="false">
                            <option value="1">正常</option>
                            <option value="2">停用</option>
                        </select>
                    </dd>  
                    <dt>　</dt>
                    <dd><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="save('menu')">保存</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_menu').dialog('close')">取消</a></dd>
                </dl>
            </form>
        </div>   
    </div>
<script>
    var menu;
    var module = new CreateData("#dg","datagrid","/system/module",'moid','#dlg','#fm');
    // module.getData('mo-list');
    $(function(){
        $('#center').hide();
    
        $('#dg').datagrid({
            url:"<?php echo Url::base();?>/system/module/mo-list", 
            onDblClickRow:function(rowIndex,rowData){
                module.editData('mo-edit','编辑模块');
            },
            onClickRow:function(rowIndex,rowData){
                $('#center').show();
                menu = new CreateData("#tg","treegrid","/system/menu",'sm_id','#dlg_menu','#fm_menu');
                // menu.getData('list?tag='+rowData.mo_tag);
                $('#tg').treegrid({
                    url:"<?php echo Url::base();?>/system/menu/list?tag="+rowData.mo_tag, 
                    onDblClickRow:function(rowIndex,rowData){
                        menu.editData('menu-edit','编辑菜单');
                    }
                });
            }
        });
        $('#sm_menu_png').combogrid({  
            panelWidth:200,  
            idField:'name',  
            textField:'name',  
            url:"<?php echo Url::base();?>/system/menu/png-list",  
            fitColumns:true,
            columns:[[  
                {field:'name',title:'名称',width:140},  
                {field:'image',title:'图片',width:60,align:'center',formatter:function(value,row,index){return '<img src="'+row.image+'" />';}}
            ]] 
        }); 
    });
    function format_status(val,row)
    {
        if (val == 1) {
        return '<span style="color:green;">启用</span>';
        } else {
        return '<span style="color:red;">停用</span>';
        }
    } 

    function format_img(val,row)
    {
        if(row.sm_menu_png){
            return '<img src="<?php  echo Url::base();?>/theme/easyui131/themes/icons/'+row.sm_menu_png+'" />';
        }
    } 
    function add(type)
    {
        var row = $('#dg').datagrid('getSelected');
        // var rowIndex=$('#dg').datagrid('getRowIndex',$('#dg').datagrid('getSelected'))
        // alert(row.moid);
        if(type=='module'){
            module.addData('mo-add','新增模块');
            $('#mo_status').combobox('select',1);
        }else if(type=='menu'){
            menu.addData('menu-add','新增菜单');
            $('#sm_status').combobox('select',1);
            // $('#sm_parent_id').combotree('setValue',row.moid).combotree('setText',row.mo_title);
            $('#sm_tag').val(row.mo_tag);
        }
    }
    function edit(type)
    {
        if(type=='module'){
            module.editData('mo-edit','编辑模块');
        }else if(type=='menu'){
            menu.editData('menu-edit','编辑菜单');
        }
    }
    function destroy(type)
    {
        if(type=='module'){
            module.delData('mo-del');
        }else if(type=='menu'){
            menu.delData('menu-del');
        }
    }

    function save(type)
    {
        if(type=='module'){
            module.save();
        }else if(type=='menu'){
            menu.save();
        }
    }
</script> 
</body>
</html>