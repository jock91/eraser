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
/*
 *
 * Récupérer heure PHP
 *
 *
 */
	var date = new Date,
		h = date.getHours(),
		m = date.getMinutes(),
		s = date.getSeconds();
	if(h<10){h = "0"+h;}
	if(m<10){m = "0"+m;}
	if(s<10){s = "0"+s;}
	$('.sideTimer').html(h+':'+m+':'+s);
}