$(document).ready(function() {
	numberTimes = Math.floor($("#imageContent").width() / imageOffset);
	
	$("#imagePrev").fadeTo("fast", fHideValue);
	$("#imageNext").fadeTo("fast", fHideValue);
	
	
	$("#imagePrev").hover(function() {
		$(this).stop();
		$(this).fadeTo("normal", 1);
	}, function() {
		$(this).stop();
		$(this).fadeTo("normal", fHideValue);
	});
	$("#imageNext").hover(function() {
		$(this).stop();
		$(this).fadeTo("normal", 1);
	}, function() {
		$(this).stop();
		$(this).fadeTo("normal", fHideValue);
	});
	
	
	$("#imagePrev").click(function() {
		clearInterval(timeInterval);
		iPrev = numberTimes;
		_tw = 0;
		timeInterval = setInterval("prev()", 1);
	});
	$("#imageNext").click(function() {
		clearInterval(timeInterval);
		iNext = numberTimes;
		_tw = 0;
		timeInterval = setInterval("next()", 1);
	});
	$("#imageCc img").each(function() {
		initUpload($(this));
	});
});

function initUpload(imageObject)
{
	imageObject.fadeTo("fast", 0.5);
	imageObject.hover(function() {
		$(this).stop();
		$(this).fadeTo("normal", 1);
	}, function() {
		$(this).stop();
		$(this).fadeTo("normal", 0.5);
	});
	imageObject.click(function() {
		if (_tw == 0) {
			clearInterval(timeInterval);
			var offset = $(this).offset();
			$("#imageCc img").css("border-color", "#CCCCCC");
			$(this).css("border-color", "#208C00");
			var pOffset = $("#imageContent").offset();
			var _object = $("#showBigImage").find("[src='" +$(this).attr("bigSrc") + "']");
			selectedImageea = $(this).attr("id");
			if (typeof(_object.attr("src")) == "undefined") {
				var _target = '<img src="' + $(this).attr("bigSrc") + '" />';
				$("#showBigImage").html(_target);
			}
			COffset(offset.left - pOffset.left, $(this).width());
		}
	});
}

var selectedImageea = false;
var prevImage;
var timeInterval;
var imageOffset = 1;
var numberTimes = 0;
var iPrev = numberTimes;
var iNext = numberTimes;
var fHideValue = 0.1;
function resetBar()
{
	$("#imageCc img").unbind();
	$("#imageCc img").remove();
	$("#showBigImage").empty();
}

function prev()
{
	if (iPrev == 0) {
		clearInterval(timeInterval);
		return;
	}
	$("#imageContent").scrollLeft($("#imageContent").scrollLeft() + imageOffset);
	iPrev--;
}
function next()
{
	if (iNext == 0) {
		clearInterval(timeInterval);
		return;
	}
	$("#imageContent").scrollLeft($("#imageContent").scrollLeft() - imageOffset);
	iNext--;
}

var _tw = 0;
var _imageOffset;

function COffset(x, imageWidth)
{
	var _pOffset = x - ($("#imageContent").width() - imageWidth) / 2;
	var _i = 1;
	if (_pOffset < 0) {
		_i = -1;
	}
	_imageOffset = _i * imageOffset;
	_tw = Math.floor(_pOffset / _imageOffset);
	timeInterval = setInterval("_offset()", 1);
}

function _offset()
{
	if (_tw == 0) {
		clearInterval(timeInterval);
		return;
	}
	$("#imageContent").scrollLeft($("#imageContent").scrollLeft() + _imageOffset);
	_tw--;
}