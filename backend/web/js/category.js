$(function () {

	$('#datagrid').treegrid({
		url : Yii.data,
        idField : 'id',
        treeField : 'category',
		fit : true,
		fitColumns : true,
		striped : true,
		rownumbers : true,
		border : false,
		pagination : false,
        lines : true,
        checkOnSelect : true,
        selectOnCheck : true,
        onContextMenu: onContextMenu,
		pageSize : 30,
		pageList : [10, 20, 30, 40, 50],
		pageNumber : 1,
		sortName : 'rank',
        collapsible: true,
        queryParams : {
            'module' : Yii.module
        },
		sortOrder : 'asc',
		toolbar : '#datagrid_tool',
        onLoadSuccess: function(row,param){
            $('#datagrid').treegrid('collapseAll');
        },
		columns : [[
            {
                field : 'id',
                title : '分类id',
                width : 5
            },
			{
				field : 'category',
				title : '分类名称',
				width : 100
			}
			//{
			//	field : 'rank',
			//	title : '排序',
			//	width : 50,
             //   formatter : function(value,row,index){
             //       return '<input class="rank textbox" name="'+row.id+'" value="'+row.rank+'">'
             //   }
			//}

		]]
	});
    function onContextMenu(e,row){

        if(row.pid>0){
            return false;
        }else{
            e.preventDefault();
            $(this).treegrid('select', row.id);
        }
        $('#mm').menu('show',{
            left: e.pageX,
            top: e.pageY
        });
    }
	$('#datagrid_add').dialog({
		width : 400,
		title : '编辑分类',
		modal : true,
		closed : true,
		iconCls : 'icon-user-add',
		buttons : [{
			text : '提交',
			iconCls : 'icon-add',
			handler : function () {
				if ($('#datagrid_add').form('validate')) {

					$.ajax({
						url : Yii.add,
						type : 'post',
						data : {
                            id : $('input[name="id"]').val(),
                            module : $('input[name="module"]').val(),
                            pid : $('input[name="pid"]').val(),
                            category : $('input[name="category"]').val(),
                            rank : $('input[name="rank"]').val()
						},
						beforeSend : function () {
							$.messager.progress({
								text : '正在提交中...'
							});
						},
						success : function (data, response, status) {
							$.messager.progress('close');

							if (data > 0) {
								$.messager.show({
									title : '提示',
									msg : '编辑分类成功'
								});
								$('#datagrid_add').dialog('close').form('reset');
								$('#datagrid').treegrid('reload');
							} else {
								$.messager.alert('编辑失败！', '未知错误导致失败，请重试！', 'warning');
							}
						}
					});
				}
			}
		},{
			text : '取消',
			iconCls : 'icon-redo',
			handler : function () {
				$('#datagrid_add').dialog('close').form('reset');
			}
		}]
	});



    //验证
	$('input[name="category"]').validatebox({
		required : true,
		validType : 'length[2,20]',
		missingMessage : '请输分类名称',
		invalidMessage : '分类名称在 2-20 位'
	});

	$('input[name="rank"]').validatebox({
        validType : 'number',
        invalidMessage : '排序必须是数字'
	});




	datagrid_tool = {
        rank : function (){ //排序
            var arr = {};
            $('.rank').each(function(index,obj){
                arr[$(obj).attr('name')] = $(obj).val();
            });
            console.log(arr);
            if ($('#datagrid_edit').form()) {
                $.ajax({
                    url : Yii.rank,
                    type : 'post',
                    data : {
                        rank : arr
                    },
                    beforeSend : function () {
                        $.messager.progress({
                            text : '正在排序中...'
                        });
                    },
                    success : function (data, response, status) {
                        $.messager.progress('close');

                        if (data > 0) {
                            $('#datagrid').treegrid('reload');
                        } else {
                            $.messager.alert('排序失败！', '未知错误或没有任何修改，请重试！', 'warning');
                        }
                    }
                });
            }
        },
        sub_add : function (){
            var obj = $('#datagrid').datagrid('getSelected');
            $('#select').combobox('setValue', obj.id);
            $('input[name="id"]').val('');
            //$('#select option[value='+obj.id+']').attr("selected","true");
            $('#datagrid_add').dialog('open');
            $('input[name="category"]').focus();
        },
		reload : function () {
			$('#datagrid').treegrid('reload');
		},
		redo : function () {
			$('#datagrid').treegrid('unselectAll');
		},
		add : function () {
			$('#datagrid_add').dialog('open');
			$('input[name="category"]').focus();
		},

		remove : function () {
			var rows = $('#datagrid').datagrid('getSelections');
			if (rows.length > 0) {
				$.messager.confirm('确定操作', '您确定要删除所选的记录吗？', function (flag) {
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
								ids : ids
							},
							beforeSend : function () {
								$('#datagrid').treegrid('loading');
							},
							success : function (data) {
								if (data) {
                                    var obj = $.parseJSON(data);
									$('#datagrid').treegrid('loaded');
									$('#datagrid').treegrid('load');
									$('#datagrid').treegrid('unselectAll');
									$.messager.show({
										title : '提示',
										msg : obj.info
									});
                                    $('#datagrid').treegrid('reload');
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
			var rows = $('#datagrid').treegrid('getSelections');
			if (rows.length > 1) {
				$.messager.alert('警告操作！', '编辑记录只能选定一条数据！', 'warning');
			} else if (rows.length == 1) {
				$.ajax({
					url : Yii.edit,
					type : 'post',
					data : {
						id : rows[0].id
					},
					beforeSend : function () {
						$.messager.progress({
							text : '正在获取中...'
						});
					},
					success : function (data, response, status) {
						$.messager.progress('close');

						if (data) {
                            var obj = $.parseJSON(data);
							$('#datagrid_add').form('load', {
								id : obj.id,
                                category : obj.category,
                                rank : obj.rank,
                                pid : obj.pid
							}).dialog('open');



						} else {
							$.messager.alert('获取失败！', '未知错误导致失败，请重试！', 'warning');
						}
					}
				});
			} else if (rows.length == 0) {
				$.messager.alert('警告操作！', '编辑记录请选定一条数据！', 'warning');
			}
		}
	};



});