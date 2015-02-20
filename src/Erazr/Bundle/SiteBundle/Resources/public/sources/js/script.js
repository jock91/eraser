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
}

/* ------------------------ */

function hour(){
	var date = moment().format('H:mm:ss');
	$('.sideTimer').html(date);
}