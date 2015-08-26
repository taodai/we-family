/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.font_names = '宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;'+ config.font_names;
	
	config.enterMode = CKEDITOR.ENTER_BR;  
  
    // 当输入：shift+Enter是插入的标签  
    config.shiftEnterMode = CKEDITOR.ENTER_BR;//   
      
    // 去掉ckeditor“保存”按钮  
    config.removePlugins = 'save';
	// language : 'zh-cn',
	// fullPage : true,
	
	config.extraPlugins += (config.extraPlugins ? ',lineheight' : 'lineheight');
    CKEDITOR.config.toolbar_Full =
    [
        { name: 'document',        items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'clipboard',    items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',        items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'forms',        items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles',    items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',    items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links',        items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',        items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
        '/',
        { name: 'styles',        items : [ 'Styles','Format','Font','FontSize','lineheight' ] },
        { name: 'colors',        items : [ 'TextColor','BGColor' ] },
        { name: 'tools',        items : [ 'Maximize', 'ShowBlocks','-','About' ] }
    ];
};
