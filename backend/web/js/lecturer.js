$(function () {

	$('#lecturer').datagrid({
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
		sortName : 'date',
		sortOrder : 'desc',
		toolbar : '#lecturer_tool',
		columns : [[
			{
				field : 'leid',
				title : '自动编号',
				width : 100,
				checkbox : true
			},
			{
				field : 'le_name',
				title : '讲师姓名',
				width : 100
			},
            {
                field : 'le_area',
                title : '讲师地区',
                width : 100
            },
			{
				field : 'le_pic',
				title : '照片',
				width : 50,
                height: 90,
                formatter : function(value,row,index){
                    return '<img width="50" src="'+row.le_pic+'">'
                }
			},
            {
                field : 'le_title',
                title : '讲师头衔',
                width : 100
            },
            {
                field : 'le_weight',
                title : '排名',
                width : 100
            },
            {
                field : 'le_time',
                title : '添加时间',
                width : 100
            },
		]]
	});


	$('#lecturer_add').dialog({
		width : 900,
		title : '新增讲师',
		modal : true,
		closed : true,
		iconCls : 'icon-user-add',
		buttons : [{
			text : '提交',
			iconCls : 'icon-add',
			handler : function () {
				if ($('#lecturer_add').form('validate')) {
					$.ajax({
						url : url,
						type : 'post',
						data : {
                            leid : $('#leid').val(),
                            le_name : $('input[name="le_name"]').val(),
                            le_pic : $('input[name="le_pic"]').val(),
                            le_area : $('input[name="le_area"]').val(),
                            le_title : $('input[name="le_title"]').val(),
                            le_desc : UE.getEditor('lecturer-le_desc').getContent(), //获取编辑器内容
                            le_weight : $('input[name="le_weight"]').val()
						},
						beforeSend : function () {
							$.messager.progress({
								text : '正在保存中...'
							});
						},
						success : function (data, response, status) {
							$.messager.progress('close');
							if (data > 0) {
								$.messager.show({
									title : '提示',
									msg : '保存成功'
								});
								$('#lecturer_add').dialog('close').form('reset');
								$('#lecturer').datagrid('reload');
							} else {
								$.messager.alert('保存失败！', '未知错误导致失败，请重试！', 'warning');
							}
						}
					});
				}
			}
		},{
			text : '取消',
			iconCls : 'icon-redo',
			handler : function () {
				$('#lecturer_add').dialog('close').form('reset');
			}
		}]
	});


    //验证
	$('input[name="le_name"]').validatebox({
		required : true,
		validType : 'length[2,20]',
		missingMessage : '请输入讲师姓名',
		invalidMessage : '讲师姓名在 2-20 位'
	});

	$('input[name="le_weight"]').validatebox({
        required : true,
        validType : 'number',
        missingMessage : '请输入讲师排名',
        invalidMessage : '排名必须是数字'
	});

    $('input[name="le_pic"]').validatebox({
        required : true,
        missingMessage : '请上传讲师照片'
    });


	lecturer_tool = {
		reload : function () {
			$('#lecturer').datagrid('reload');
		},
		redo : function () {
			$('#lecturer').datagrid('unselectAll');
		},
		add : function () {
            formclear();
            url = Yii.add;
			$('#lecturer_add').dialog('open');
			$('input[name="lecturer"]').focus();
		},
		remove : function () {
			var rows = $('#lecturer').datagrid('getSelections');
			if (rows.length > 0) {
				$.messager.confirm('确定操作', '您正在要删除所选的记录吗？', function (flag) {
					if (flag) {
						var ids = [];
						for (var i = 0; i < rows.length; i ++) {
							ids.push(rows[i].leid);
						}
						//console.log(ids.join(','));
						$.ajax({
							type : 'POST',
							url : Yii.remove,
							data : {
								leid : ids
							},
							beforeSend : function () {
								$('#lecturer').datagrid('loading');
							},
							success : function (data) {
								if (data) {
									$('#lecturer').datagrid('loaded');
									$('#lecturer').datagrid('load');
									$('#lecturer').datagrid('unselectAll');
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
			var rows = $('#lecturer').datagrid('getSelections');
			if (rows.length > 1) {
				$.messager.alert('警告操作！', '编辑记录只能选定一条数据！', 'warning');
			} else if (rows.length == 1) {
                formclear();
                url = Yii.edit;
                var img = '<div class="file-item thumbnail upload-state-done"><img src="'+rows[0].le_pic+'" width="70" height="90" id="pic_name" /></div></div>'
                $('#fileList').html(img);
                UE.getEditor('lecturer-le_desc').setContent(rows[0].le_desc);
                $('#lecturer_add').form('load',rows[0]).dialog('open');
			} else if (rows.length == 0) {
				$.messager.alert('警告操作！', '编辑记录至少选定一条数据！', 'warning');
			}
		}
	};



});
 function formclear(){
     UE.getEditor('lecturer-le_desc').setContent('');
     $('#lecturer_add').form('reset');
     $('input[name="leid"]').val('');
     $('#fileList').find('.thumbnail').remove();
 }