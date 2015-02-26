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

$('#btn_facebook').click(function(){
	$('#modal-facebook').modal();
	$.ajax({
		url: '/app_dev.php/connect/facebook',
		success: function(data){
			$('#modal-facebook .modal-body').html(data);
		}
	});
});