function resetForm(id)
{
	var formObject = $(id);
	formObject.form('clear');
	$(".validatebox-invalid").blur();
	$(".validatebox-invalid").removeClass("validatebox-invalid");
	$("#status").combobox("select", 1);
}

function getJson(key)
{
	var result = $(key).serializeArray();
	
	var json = {};
	for (i in result) {
		if (typeof(json[result[i].name]) == "undefined") {
			json[result[i].name] = result[i].value;
		} else {
			if (typeof(json[result[i].name]) == "string") {
				json[result[i].name] = [json[result[i].name], result[i].value];
			}
		}
	}
	return json;
}

function format_status(val,row)
{
	if (val == "1") {
		return '<span style="color:green;">启用</span>';
	} else {
		return '<span style="color:red;">停用</span>';
	}
}

var Mutual = {
	url: "",
	base_url: "",
	datagrid: "#dg",
	primary_key: "",
	gridType: "datagrid",
	query_action: "json_query",
	insert_action: "json_insert",
	update_action: "json_update",
	delete_action: "json_delete",
	
	insert_title: "新增",
	update_title: "修改",
	dlg: "#dlg",
	dlg_form: "#fm",
	search_form: "#search_form",
	
	init: function(base_url, primary_key) {
		
		this.base_url = base_url;
		
		this.primary_key = primary_key;
		if (this.gridType == "datagrid") {
			
			$(this.datagrid).datagrid({url: this.base_url + "/" + this.query_action});
		} else if(this.gridType == "treegrid") {
			$(this.datagrid).treegrid({url: this.base_url + "/" + this.query_action});
		}
	},
	
	add: function() {
		this.url = this.base_url + "/" + this.insert_action;
		resetForm(this.dlg_form);
    	$(this.dlg).dialog('open').dialog('setTitle', this.insert_title);
	},
	edit: function() {
		var row = $(this.datagrid).datagrid('getSelected');
		if (row){  
			$(this.dlg).dialog('open').dialog('setTitle', this.update_title);  
			$(this.dlg_form).form('load',row);  
			this.url = this.base_url + "/" + this.update_action + '?id=' + row[this.primary_key];  		
		} else {
			$.messager.show({  
				title: '提示',  
				msg: '请先选择一行'  
			});
		}
	},
	destroy: function() {
		var mThis = this;
		var row = $(this.datagrid).datagrid('getSelected');  
		if (row){  
			$.messager.confirm('确认','确定删除该条记录?',function(r){  
				if (r){  
					$.post(mThis.base_url + "/" + mThis.delete_action, {id: row[mThis.primary_key]},function(result){  
						if (result === true){  
							if (mThis.gridType == "datagrid") {
								$(mThis.datagrid).datagrid("reload");
							} else if(mThis.gridType == "treegrid") {
								$(mThis.datagrid).treegrid("reload");
							}
						} else if(result === false) {
							$.messager.show({  
								title: '错误提示',  
								msg: '删除失败' 
							});
						} else {  
							if(result.errorMsg ==''|| result.errorMsg ==undefined || result.errorMsg == NULL){
								$.messager.show({  
									title: '错误提示',  
									msg: result.toString() 
								});
							}else{                             
								$.messager.show({  
									title: '错误提示',  
									msg: result.errorMsg
								});
							}
						}  
					},'json');  
				}  
			});  
		} else{
			$.messager.show({  
				title: '提示',  
				msg: '请先选择一条记录' 
			});
		}
	},
	save: function() {
		var mThis = this;
		
		$(this.dlg_form).form('submit',{  
		    
            url: mThis.url,  
            onSubmit: function(){  
                return $(this).form('validate');  
            },  
            success: function(result){
			
				var result = eval('('+result+')');  
                if (result === true){
					$(mThis.dlg).dialog('close');
					if (mThis.gridType == "datagrid") {
						$(mThis.datagrid).datagrid("reload");
					} else if(mThis.gridType == "treegrid") {
						$(mThis.datagrid).treegrid("reload");
					}    
                } else if(result === false) {
							$.messager.show({  
								title: '错误提示',  
								msg: '操作失败' 
							});
				} else  {
					if(result.errorMsg ==''|| result.errorMsg ==undefined || result.errorMsg == null){
						$.messager.show({  
							title: '错误提示',  
							msg: result.toString() 
						});
					}else{
						$.messager.show({  
							title: '错误提示',  
							msg: result.errorMsg 							
						});
					}
                }  
            }  
        }); 
	},
	searchLoad: function() {
		$(this.datagrid).datagrid("load",getJson(this.search_form));
	},
	searchReset: function() {
		$(this.search_form).form('clear');
		$(this.datagrid).datagrid('load', {});
	},
	closeDlg: function() {
		$(this.dlg).dialog('close');
	}
}

var ckeditConfig = {
	pasteFromWordRemoveStyles: true,  
	filebrowserImageUploadUrl: "/web_admin/manage_news/upload"  
}