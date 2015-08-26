$(function () {

	$('#datagrid').datagrid({
        url : Yii.data,
        fit : true,
        fitColumns : true,
        striped : true,
        rownumbers : true,
        border : false,
        pagination : true,
        pageSize : 20,
        pageList : [10, 20, 30, 40, 50],
        pageNumber : 1,
        sortName : 'addtime',
        sortOrder : 'desc',
        toolbar : '#tool-btn',
        columns : [[
            {
                field : 'id',
                title : '自动编号',
                width : 100,
                checkbox : true
            },
            {
                field : 'title',
                title : '标题',
                width : 100
            },
            {
                field : 'category',
                title : '所属分类',
                width : 50,
                formatter : function(value,row,index){
                    if(row.category!=null) {
                        return row.cate.category
                    }
                }
            },
            {
                field : 'pirUrl',
                title : '图片',
                width : 50,
                height: 90,
                formatter : function(value,row,index){
                    if(row.picUrl==''){
                        return '暂无图片'
                    }else{
                        return '<img width="50" src="'+row.picUrl+'">'
                    }

                }
            },
            {
                field : 'info',
                title : '概述',
                width : 100
            },
            {
                field : 'addtime',
                title : '添加时间',
                width : 50
            }


        ]]
	});
    //ajax提交表单
    $('#ajaxForm').form({
        onSubmit: function(param){
            if(flag){
                $.messager.alert('警告！', '已存在该标题的信息！', 'warning');
                return false;
            }
            if($('input[name="category"]').val()==''){
                $.messager.alert('警告！', '必须选择一个分类！', 'warning');
                return false;
            }
            if($('textarea[name="info"]').val().length>120){
                $.messager.alert('警告！', '概述不得大于120个字！', 'warning');
                return false;
            }
            var p = $('#month').combotree('getValues');
            param.months = p;
            $.messager.progress({
                text : '正在提交中...'
            });
        },
        success:function(data){
            $.messager.progress('close');
            if(data>0){
                $.messager.alert('提示', '提交成功', 'info',function(){
                        $('#ajaxForm').form('reset');
                        $('.thumbnail').remove();
                        UE.getEditor('appwiki-content').execCommand('cleardoc');
                        parent.$('#maintab').tabs('select', '百科管理');
                        parent.frames['icon-news-47'].$('#datagrid').datagrid('reload');
                        //parent.$('#maintab').tabs('getSelected');
                        //parent.$('#maintab').tabs('update', {
                        //    tab: tab,
                        //    options: {
                        //        title: '百科管理',
                        //        content: '<iframe scrolling="auto" frameborder="0"  src="'+Yii.index+'" style="width:100%;height:100%;"></iframe>'
                        //    }
                        //});

                });

            }else{
                $.messager.alert('提示', data, 'error');
            }
        }
    });
    $('#st').click(function(){
        $('#ajaxForm').submit();
    });

    //验证
    $('input[name="title"]').validatebox({
        required : true,
        missingMessage : '请输入标题'
    });
    $('input[name="tag"]').validatebox({
        validType : 'length[0,100]',
        invalidMessage : '标签最多100个字'
    });
    $('textarea[name="info"]').validatebox({
        validType : 'length[0,120]',
        invalidMessage : '概述最多120个字'
    });

	datagrid_tool = {
		reload : function () {
			$('#datagrid').datagrid('reload');
		},
        reall : function () {
            $('#category_datagrid').datagrid('unselectAll');
            $('#body-layout').layout('panel','center').panel({title:'百科管理'});
            $('#search').searchbox('setValue','');
            $('#datagrid').datagrid({
                url:Yii.data
            });
            url = Yii.data;
        },
		redo : function () {
			$('#datagrid').datagrid('unselectAll');
		},
		add : function () {
            if (parent.$('#maintab').tabs('exists', '添加百科')) {
                parent.$('#maintab').tabs('select', '添加百科');
            } else {
                parent.$('#maintab').tabs('add', {
                    title : '添加百科',
                    iconCls : 'icon-add',
                    closable : true,
                    content :'<iframe scrolling="auto" frameborder="0"  src="'+Yii.add+'" style="width:100%;height:100%;"></iframe>',
                    url:Yii.add
                });
            }
		},
        remove : function () {
            var rows = $('#datagrid').datagrid('getSelections');
            if (rows.length > 0) {
                $.messager.confirm('确定操作', '您正在要删除所选的记录吗？', function (flag) {
                    if (flag) {
                        var ids = [];
                        for (var i = 0; i < rows.length; i ++) {
                            ids.push(rows[i].id);
                        }
                        //console.log(ids.join(','));
                        $.ajax({
                            type : 'POST',
                            url : Yii.remove,
                            data : {
                                id : ids
                            },
                            beforeSend : function () {
                                $('#lecturer').datagrid('loading');
                            },
                            success : function (data) {
                                if (data) {
                                    $('#datagrid').datagrid('loaded');
                                    $('#datagrid').datagrid('load');
                                    $('#datagrid').datagrid('unselectAll');
                                    $.messager.show({
                                        title : '提示',
                                        msg : data + '个数据被删除成功！'
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                $.messager.alert('提示', '请选择要删除的记录！', 'info');
            }
        },
		edit : function () {
            var parent_tab = parent.$('#maintab');

            var rows = $('#datagrid').datagrid('getSelections');
            var url = '<iframe scrolling="auto" frameborder="0"  src="'+Yii.edit+'?id='+rows[0].id+'" style="width:100%;height:100%;"></iframe>';
            if (rows.length > 1) {
                $.messager.alert('警告操作！', '编辑记录只能选定一条数据！', 'warning');
            } else if (rows.length == 1) {
                if (parent_tab.tabs('exists', '修改百科')) {
                    parent_tab.tabs('select', '修改百科');
                    var tab = parent_tab.tabs('getSelected');
                    parent_tab.tabs('update', {
                        tab: tab,
                        options: {
                            title: '修改百科',
                            content: url
                        }
                    });

                } else {
                    parent_tab.tabs('add', {
                        title : '修改百科',
                        iconCls : 'icon-edit',
                        closable : true,
                        content : url
                    });
                }
            } else if (rows.length == 0) {
                $.messager.alert('警告操作！', '编辑记录至少选定一条数据！', 'warning');
            }
		}
	};



});
    function combotree(id,lines,multiple,checkbox){
        $('#'+id).combotree({
            url : Yii.month,
            lines : lines,
            multiple : multiple,
            checkbox : checkbox,
            onLoadSuccess : function (node, data) {
                var _this = this;
                if (data) {
                    $(data).each(function (index, value) {

                        if (this.state == 'closed') {
                            $(_this).tree('collapseAll');
                        }
                    });
                }
            }

        });
    }