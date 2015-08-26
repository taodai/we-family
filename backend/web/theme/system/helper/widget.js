function resetForm(id)
{
    var formObject = $(id);
    formObject.form('clear');
    $(".validatebox-invalid").blur();
    $(".validatebox-invalid").removeClass("validatebox-invalid");
}

/*
* containerId 容器ID ;
* gridType 数据类型 ;
* baseUrl 基础URL ;
* primaryKey 主键 ;
* dlgId 添加/编辑容器ID ;
* dlgFormId 添加/编辑FORM表单ID ;
*/

function CreateData(containerId,gridType,baseUrl,primaryKey,dlgId,dlgFormId)
{
    this.url = '';
    this.dlgId = dlgId;
    this.dlgFormId = dlgFormId;
    this.containerId = containerId;
    this.gridType = gridType;
    this.baseUrl = baseUrl;
    this.primaryKey = primaryKey;
}

CreateData.prototype={  
    getData:function(action)  
    {  
        if (this.gridType == "datagrid") {
            $(this.containerId).datagrid({url: this.baseUrl + "/" + action});
        } else if(this.gridType == "treegrid") {
            $(this.containerId).treegrid({url: this.baseUrl + "/" + action});
        }
    }, 
    addData: function(action,title) {
        this.url = this.baseUrl + "/" + action;
        resetForm(this.dlgFormId);
        $(this.dlgId).dialog('open').dialog('setTitle', title);
    }, 
    editData: function(action,title) {
        var row =  '';
        if(this.gridType == 'datagrid'){
            row = $(this.containerId).datagrid('getSelected'); 
        }else{
            row = $(this.containerId).treegrid('getSelected'); 
        }
        if (row){  
            $(this.dlgId).dialog('open').dialog('setTitle', title);  
            $(this.dlgFormId).form('load',row);  
            this.url = this.baseUrl + "/" + action + '?id=' + row[this.primaryKey];       
        } else {
            $.messager.show({  
                title: '提示',  
                msg: '请先选择一行'  
            });
        }
    },
    delData: function(action,type) {
        var row =  '';
        var deleteData = this;
        if(this.gridType == 'datagrid'){
            row = $(this.containerId).datagrid('getSelected'); 
        }else{
            row = $(this.containerId).treegrid('getSelected'); 
        }
        if (row){  
            if(type){
                if(type=='enable'){
                    var msg = '确定启用该条记录?';
                }else{
                    var msg = '确定删除或停用该条记录?';
                }
            }else{
                var msg = '确定删除或停用该条记录?';
            }
            $.messager.confirm('确认',msg,function(r){  
                if (r){  
                    $.post(deleteData.baseUrl + "/" + action, {id: row[deleteData.primaryKey]},function(result){  
                        if (result.success === true){  
                            if (deleteData.gridType == "datagrid") {
                                $(deleteData.containerId).datagrid("reload");
                            } else if(deleteData.gridType == "treegrid") {
                                $(deleteData.containerId).treegrid("reload");
                            }
                        } else if(result.success === false) {
                            $.messager.show({  
                                title: '错误提示',  
                                msg: result.errorMsg 
                            });
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
        var Data = this;
        $(Data.dlgFormId).form('submit',{  
            url: Data.url,  
            onSubmit: function(){  
                return $(this).form('validate');  
            },  
            success: function(result){
                var result = eval('('+result+')'); 
                if (result.success){
                    $(Data.dlgId).dialog('close');
                    if (Data.gridType == "datagrid") {
                        $(Data.containerId).datagrid("reload");
                    } else if(Data.gridType == "treegrid") {
                        $(Data.containerId).treegrid("reload");
                    }    
                } else if(result.success === false) {
                            $.messager.show({  
                                title: '错误提示',  
                                msg: result.errorMsg
                            });
                } 
            }  
        }); 
    }
}  