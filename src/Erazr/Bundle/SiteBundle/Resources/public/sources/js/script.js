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
// Augmenter la hauteur d'un textarea au clic
	growTextarea();
// FlashMessages
	flashMessage();
// Notifications
	notifs();
// Formulaire de recherche
	recherche_ajax();
// Chat
	chat();
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

function growTextarea(){
	$('textarea.grow').click(function(){
		var open = $(this).data('open'),
			el = $(this);
		if(open == false){
			$(el).stop().animate({'min-height':'10rem'});
			$(el).data('open',true);
		}
	});
	$('*').click(function(e){
		if(!$(e.target).is('textarea.grow')){
			var textarea = $('textarea.grow'),
				open = $(textarea).data('open');
			if(open == true && $(textarea).val() < 1){
				$(textarea).stop().animate({'min-height':'3.4rem'});
				$(textarea).data('open',false);
			}
		}
	});
}

function flashMessage(){
	$('.flash').hide().fadeIn('slow').delay(5000).fadeOut('slow',function(){$('ul.flash').remove();});
	$('.flash-close').click(function(){
		$(this).parent().fadeOut('slow',function(){$('ul.flash').remove();});
		return false;
	});
}

function notifs(){
	$('.notif-icon').click(function(){
		var open = $('.notif-alert ul').data('open');
		if(open == false){
			$('.notif-alert ul').fadeIn();
			$('.notif-alert ul').data('open',true);
		}else{
			$('.notif-alert ul').fadeOut();
			$('.notif-alert ul').data('open',false);
		}
		return false;
	});
	$('*').click(function(e){
		if(!$(e.target).is('.notif-alert *')){
			var open = $('.notif-alert ul').data('open');
			if(open == true){
				$('.notif-alert ul').fadeOut();
				$('.notif-alert ul').data('open',false);
			}
		}
	});
}

function recherche_ajax(){
	$('#form_recherche').keyup(function(key){
		var el		= $(this),
			input	= $(el).find('input');
		console.log($(input).val());
		if($(input).val().length >= 3 || $(input).val() == ""){
			$.ajax({
				url: $(el).attr('action'),
				data: $(el).serialize(),
				success: function(data){
					console.log(data);
				}
			});
		}
		return false;
	});
}

function chat(){
	$('.chat').each(function(){
		var el = $(this),
			open = $(el).data('open');
		if(open == false){
			$(el).find('.chat-close').hide();
		}
	});
	$(document).on('click','.chat .chat-header a',function(){
		var el = $(this),
			open = $(el).parents('.chat').data('open');
		if(open == true){
			$(el).parents('.chat').data('open',false).find('.chat-close').hide();
		}else{
			$(el).parents('.chat').data('open',true).find('.chat-close').show();
		}
		return false;
	});
	$(document).on('click','.chat .chat-header .icon-close',function(){
		$(this).parents('.chat').remove();
		return false;
	});
	$('.chat-btn').click(function(){
		var username = $(this).data('username'),
			chat = $('.chat[data-username="'+username+'"]');
		if($('.chat').length == 2 && $(chat).length == 0){
			console.log('length == 2');
			$('body').prepend('
				<ul class="flash">
				<li class="flash-error"><span class="flash-icon"><span class="icon-close"></span></span>
				Vous ne pouvez avoir que 2 chat d\'ouvert simultanement
				<a href="#" class="flash-close"><span class="icon-close"></span></a>
				</li>
				</ul>
			');
			flashMessage();
		}else{
			if($(chat).length > 0){
				console.log('length > 0');
				var open = $(chat).data('open');
				if(open == false){
					$(chat).data('open',true).find('.chat-close').show();
				}
			}else{
				console.log('length == 0');
				$('.chat-container').append("<div class='chat' data-username="+username+" data-open='true'><div><div class='chat-header'><a href='#'>"+username+"<span class='icon-close'></span></a></div><div class='chat-close chat-content'></div><div class='chat-close chat-footer'><form><input type='text' placeholder='Ecrire un message' class='form-control' /></form></div></div>");
			}
		}
		return false;
	});
}

/* ------------------------ */

// Fix facebook connect
	if(window.location.hash == '#_=_'){
		window.location.hash = '';
		history.pushState('', document.title, window.location.pathname);
		e.preventDefault();
	}