$(function(){
	init();
	$(window).resize(function(){
		resize();
	});
});

function init(){
	resize();
	hour();
	setInterval(hour,1000);
}

function resize(){
	fullHeight('aside','min');
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

function hour(){
	var time = moment().format('H:mm:ss');
	$('.sideTimer').html(time);
}