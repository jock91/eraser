$(function(){
	init();
	$(window).resize(function(){
		resize();
	});
});

function init(){
	resize();
// Timer aside
	hour();
	setInterval(hour,1000);
// Init des tooltips
	$('[data-toggle="tooltip"]').tooltip();
// Timer sur les posts
	countdown();
// Affichage depuis combien de temps c'est posté
	created();
// Limite de caractères sur les posts de l'accueil
	splitPost();
}

function resize(){
	fullHeight('aside','min');
}

/* ------------------------ */

function fullHeight(e,min){
	var winH = $(document).height();
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

function countdown(){
	$('[data-countdown]').each(function(){
		var el = $(this),
			finalDate = $(this).data('countdown');
		$(el).countdown(finalDate,function(event){
			var days = event.strftime('%-D'),
				hours = event.strftime('%H');
			if(days != 0){var days = '24';}
			var hours = parseInt(days) + parseInt(hours);
			if(hours < '10'){var hours = '0'+hours;}
			var timer = event.strftime(hours+':%M:%S');
			$(el).html(timer);
			if(timer == "00:00:00" && $(el).parents('.post').hasClass('post-split')){
				$(el).delay(100000).parents('.column-item').slideUp(500,function(){$(this).remove();});
				console.log('done');
			}
		});
	});
}

function created(){
	$('[data-created]').each(function(){
		moment.locale('fr');
		var created = $(this).data('created'),
			created = moment(created,'YYYY/MM/DD H:m:s').fromNow();
		$(this).html(created);
	});
}

function splitPost(){
	$('.post-split').each(function(){
		var str = $(this).find('.content').text(),
			maxChars = 150;
		if(str.length > maxChars){
			str = str.substring(0,maxChars);
			$(this).find('.content').html(str+' [...]');
		}
	});
}

/* ------------------------ */

// Fix facebook connect
	if(window.location.hash == '#_=_'){
		window.location.hash = '';
		history.pushState('', document.title, window.location.pathname);
		e.preventDefault();
	}