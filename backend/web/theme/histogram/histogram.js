function histogram(){
	var controls={};
	var bgColor=new Array("#666666","#21AA7C","#2277BB","#dc7644","#BBAA22","#AA22AA","#338800","#1099EE","#ffcc33","#ED3810");
	this.init=function(data,y){
		setControls();
		buildHtml(data,y);
	}
	function setControls(){
		controls.histogramContainer=$("#histogram-container");
		controls.histogramBgLineUL=controls.histogramContainer.children("div.histogram-bg-line");
		controls.histogramContentUL=controls.histogramContainer.children("div.histogram-content");
		controls.histogramY=controls.histogramContainer.children("div.histogram-y");
	}
	function buildHtml(data,y){
		var len=data.length,perArr=new Array(),maxNum,maxTotal,yStr='';
		var contentStr='',bgLineStr='',bgLineAll='';
		var widthPer=Math.floor(100/len);
		minWidth=len*21+60;
		controls.histogramContainer.css("min-width",minWidth+"px");
		
		for(var a=0;a<len;a++){
			perArr[a]=parseInt(data[a]['ii_weight_total']);		
		}
		maxNum=String(perArr.max());
		if(maxNum.length>2){
			var x=parseInt(maxNum.substr(maxNum.length-2,1))+1;
			maxTotal=Math.floor(parseInt(maxNum/100))*100+x*10;
		}else{
			maxTotal=Math.floor(parseInt(maxNum/10))*10+10;
		}
		
		//y轴部分
		if(y=="%"){
			yStr+='<li>100%</li><li>80%</li><li>60%</li><li>40%</li><li>20%</li><li>0%</li>';			
		}else{
			var avg=maxTotal/5;
			for(i=5;i>=0;i--){
				yStr+='<li>'+avg*i+'</li>';
			}
		}

		
		//柱状条部分
		for(var i=0;i<len;i++){
			var per=Math.floor(parseInt(data[i]['ii_weight_total'])/maxTotal*100);
			var n=Math.floor(parseInt(per)/10);
			contentStr+='<li style="width:'+widthPer+'%">';
			contentStr+='<span class="histogram-box"><a style="height:'+per+'%'+';background:'+bgColor[n]+';" title="'+data[i]['ii_weight_total']+'"></a></span><span class="histogram-name">'+data[i]['name']+'</span>';
			contentStr+='</li>';
			bgLineStr+='<li style="width:'+widthPer+'%;"><div></div></li>';
		}
		//背景方格部分
		for(var j=0;j<5;j++){
			bgLineAll+='<ul>'+bgLineStr+'</ul>';
		}
		bgLineAll='<div class="histogram-bg-line">'+bgLineAll+'</div>';
		contentStr='<div class="histogram-content"><ul>'+contentStr+'</ul></div>';
		yStr='<div class="histogram-y"><ul>'+yStr+'</ul></div>';
		controls.histogramContainer.html(bgLineAll+contentStr+yStr);
		controls.histogramContainer.css("height",controls.histogramContainer.height()+"px");//主要是解决IE6中的问题		
	}
}
Array.prototype.max = function(){//最大值
 return Math.max.apply({},this) 
} 

