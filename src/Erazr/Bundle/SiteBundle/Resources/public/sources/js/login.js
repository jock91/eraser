$(function(){
	init();
	$(window).resize(function(){
		resize();
	});
});

function init(){
	resize();
}

function resize(){
	fullHeight('#login','min');
}

/* ------------------------ */

function fullHeight(e,min){
	var winH = $(window).height();
	if(min){
		$(e).css('min-height',winH);
	}else{
		$(e).css('height',winH);
	}
}