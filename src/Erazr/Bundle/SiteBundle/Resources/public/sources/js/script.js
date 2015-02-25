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
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-countdown]').each(function(){
		var el = $(this),
			finalDate = $(this).data('countdown');
		$(el).countdown(finalDate,function(event){
			var days = event.strftime('%-D'),
				hours = event.strftime('%H');
			if(days != 0){var days = '24';}
			var hours = parseInt(days) + parseInt(hours);
			if(hours < '10'){var hours = '0'+hours;}
			$(el).html(event.strftime(hours+':%M:%S'));
		});
	});
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