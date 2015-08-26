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
		sortName : 'date',
		sortOrder : 'desc',
		toolbar : '#datagrid_tool',
        onRowContextMenu: onContextMenu,
		columns : [[
			{
				field : 'cnid',
				title : '自动编号',
				width : 100,
				checkbox : true
			},
			{
				field : 'theme',
				title : '课程主题',
				width : 100
			},
			{
				field : 'theme_pic',
				title : '主题图片',
				width : 50,
                height: 90,
                formatter : function(value,row,index){
                    return '<img width="50" src="'+row.theme_pic+'">'
                }

			},
			{
				field : 'share_lecturer',
				title : '分享讲师',
				width : 100,
                formatter : function(value,row,index){
                    if(row.lecturer!=null) {
                        return row.lecturer.le_name
                    }
                }
			},
            {
                field : 'share_desc',
                title : '分享概述',
                width : 100
            },
            {
                field : 'share_time',
                title : '分享时间',
                width : 100
            },
            {
                field : 'share_qrcode',
                title : '分享群二维码',
                width : 100,
                formatter : function(value,row,index){
                    return '<img width="50" src="'+row.share_qrcode+'">'
                }
            },
            {
                field : 'courseReview',
                title : '课堂回顾',
                width : 100,
                formatter : function(value,row,index){
                    if(row.courseReview){
                        return '<a href="javascript:void(0);" data="'+row.courseReview.cnid+'" iconCls="icon-add" class="button" style="width:80px;">查看</a>';
                    }else{
                        return '暂无回顾'
                    }
                }
            }
		]],
        onLoadSuccess:function(data){
            $('.button').linkbutton({text:'查看',iconCls:'icon-search'});
        }
	});

    $(document).on("click", ".button", function(){
        var cnid = $(this).attr('data');
        $.ajax({
            url : Yii.one,
            type : 'post',
            data : {
                cnid : cnid
            },
            beforeSend : function () {
                $.messager.progress({
                    text : '正在加载...'
                });
            },
            success : function (data, response, status) {
                var obj = $.parseJSON(data);
                $.messager.progress('close');
                $('#review').dialog('open');
                $('#review_content').html(obj.courseReview.content);
            }

        });
    });

    $('#review').dialog({
        title: '内容回顾',
        width: 640,
        height: 480,
        closed: true,
        cache: false,
        resizable : true,
        modal: true
    });
    //验证
    $('input[name="theme"]').validatebox({
        required : true,
        validType : 'length[2,50]',
        missingMessage : '请输入课程主题',
        invalidMessage : '课程主题在 2-50 位'
    });
    $('input[name="share_time"]').validatebox({
        required : true,
        missingMessage : '请输入分享时间'
    });
	$('#dlg_add').dialog({
		width : 900,
        height: 500,
		title : '新增微信课堂',
		modal : true,
		closed : true,
		buttons : [{
			text : '提交',
			iconCls : 'icon-add',
			handler : function () {
				if ($('#dlg_add').form('validate')) {
                    var share_desc;
                    if($('#confirm').attr('checked')){
                        share_desc = UE.getEditor('coursenotice-share_content').getContentTxt();
                        if(share_desc.length > 100) {
                            share_desc = share_desc.substring(0,100);
                        }
                    }else{
                        share_desc = $('input[name="share_desc"]').val()
                    }

					$.ajax({
						url : url,
						type : 'post',
						data : {
                            cnid : $('#cnid').val(),
                            theme : $('input[name="theme"]').val(),
                            theme_pic : $('#theme_pic').val(),
                            share_lecturer : $('input[name="share_lecturer"]').val(),
                            share_desc : share_desc,//$('input[name="share_desc"]').val(),
                            share_content : UE.getEditor('coursenotice-share_content').getContent(), //获取编辑器内容
                            share_time : $('input[name="share_time"]').val(),
                            share_qrcode : $('input[name="share_qrcode"]').val()
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
								$('#dlg_add').dialog('close').form('reset');
								$('#datagrid').datagrid('reload');
							} else {
								$.messager.alert('保存失败！', '未知错误导致失败，请重试！', 'warning');
							}
						},
                        error : function(){

                        }
					});
				}
			}
		},{
			text : '取消',
			iconCls : 'icon-redo',
			handler : function () {
				$('#dlg_add').dialog('close').form('reset');
			}
		}]
	});
    $('#dlg_show').dialog({
        width : 900,
        title : '课堂回顾',
        modal : true,
        closed : true,
        buttons : [{
            text : '提交',
            iconCls : 'icon-add',
            handler : function () {
                if ($('#dlg_show').form('validate')) {
                    $.ajax({
                        url : Yii.review,
                        type : 'post',
                        data : {
                            cnid : $('#themeid').val(),
                            id : $('#crid').val(),
                            content : UE.getEditor('coursereview-content').getContent() //获取编辑器内容
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
                                $('#dlg_show').dialog('close').form('reset');
                                $('#datagrid').datagrid('reload');
                            } else {
                                $.messager.alert('保存失败！', '未知错误导致失败，请重试！', 'warning');
                            }
                        },
                        error : function(){

                        }
                    });
                }
            }
        },{
            text : '取消',
            iconCls : 'icon-redo',
            handler : function () {
                $('#dlg_show').dialog('close').form('reset');
            }
        }]
    });

    //右键菜单
    function onContextMenu(e,row){
        e.preventDefault();
        $(this).treegrid('select', row.id);
        $('#mm').menu('show',{
            left: e.pageX,
            top: e.pageY
        });
    }

    datagrid_tool = {
        sub_add : function (){
            UE.getEditor('coursereview-content').setContent('');
            var obj = $('#datagrid').datagrid('getSelected');
            if(obj == null){
                $.messager.alert('警告！', '请先点击选择一条数据', 'warning');
            }else{
                $('#theme').val(obj.theme);
                $('#themeid').val(obj.cnid);
                if(obj.courseReview != null){
                    $('#crid').val(obj.courseReview.id);
                    UE.getEditor('coursereview-content').setContent(obj.courseReview.content);
                }else{
                    $('#crid').val('');
                }
                $('#dlg_show').dialog('open');
            }

        },
		reload : function () {
			$('#datagrid').datagrid('reload');
		},
		redo : function () {
			$('#datagrid').datagrid('unselectAll');
		},
		add : function () {
            formclear();
            url = Yii.add;
			$('#dlg_add').dialog('open');
			$('input[name="theme"]').focus();
		},
		remove : function () {
			var rows = $('#datagrid').datagrid('getSelections');
			if (rows.length > 0) {
				$.messager.confirm('确定操作', '您正在要删除所选的记录吗？', function (flag) {
					if (flag) {
						var ids = [];
						for (var i = 0; i < rows.length; i ++) {
							ids.push(rows[i].cnid);
						}
						//console.log(ids.join(','));
						$.ajax({
							type : 'POST',
							url : Yii.remove,
							data : {
								cnid : ids
							},
							beforeSend : function () {
								$('#datagrid').datagrid('loading');
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
			var rows = $('#datagrid').datagrid('getSelections');
			if (rows.length > 1) {
				$.messager.alert('警告操作！', '编辑记录只能选定一条数据！', 'warning');
			} else if (rows.length == 1) {
                formclear();
                url = Yii.add;
                var img = '<span class="delete">X</span><div class="file-item thumbnail upload-state-done"><img src="'+rows[0].theme_pic+'" width="170" height="80" id="theme_pic" /></div></div>'
                var code_img = '<span class="delete">X</span><div class="file-item thumbnail upload-state-done"><img src="'+rows[0].share_qrcode+'" width="80" height="80" id="share_qrcode" /></div></div>'
                $('#fileList').html(img);
                $('#fileList2').html(code_img);
                $('#dlg_add').form('load',rows[0]).dialog('open');
                if(rows[0].share_content){
                    UE.getEditor('coursenotice-share_content').setContent(rows[0].share_content);
                }

			} else if (rows.length == 0) {
				$.messager.alert('警告操作！', '编辑记录至少选定一条数据！', 'warning');
			}
		}
	};



});
function formclear(){
    $('#dlg_add').form('reset');
    UE.getEditor('coursenotice-share_content').setContent('');
    $('input[name="cnid"]').val('');
    $('#theme_pic').val('');
    $('#fileList').find('.thumbnail').remove();
    $('#fileList2').find('.thumbnail').remove();
}

function delHtmlTag(str){
    return str.replace(/<[^>]+>/g,"");//去掉所有的html标记
}