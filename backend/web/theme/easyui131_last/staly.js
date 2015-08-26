 var easyuiPanelOnMove = function(left, top) {
        var parentObj = $(this).panel('panel').parent();
        if (left < 0) {
            $(this).window('move', {
                left : 1
            });
        }
        if (top < 0) {
            $(this).window('move', {
                top : 1
            });
        }
        var width = $(this).panel('options').width;
        var height = $(this).panel('options').height;
        var right = left + width;
        var buttom = top + height;
        var parentWidth = parentObj.width();
        var parentHeight = parentObj.height();
        if(parentObj.css("overflow")=="hidden"){
            if(width=='auto'){      //如果宽度为auto,就给他具体值，不然右移就会超出边界                           
                width=parentWidth/2-50;
                }   
            if(left > parentWidth-width){
                $(this).window('move', {
                    "left":parentWidth-width
                });
        }
            if(top > parentHeight-$(this).parent().height()){
                $(this).window('move', {
                    "top":parentHeight-$(this).parent().height()
                });
        }
        }
    };
$.fn.panel.defaults.onMove = easyuiPanelOnMove;
$.fn.window.defaults.onMove = easyuiPanelOnMove;
$.fn.dialog.defaults.onMove = easyuiPanelOnMove;


// $(document).keyup(function(e){
//     var code = e.keyCode ? e.keyCode : e.which;
//     if(code == 27 || code == 96) $(".easyui-dialog").dialog('close');
//   });