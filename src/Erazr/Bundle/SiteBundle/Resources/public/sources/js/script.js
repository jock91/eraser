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
// Chat
	chat();
// Search
	search_ajax();
// Likes
	like_ajax();
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
	$(document).on('click','.chat-btn',function(){
		var username = $(this).data('username'),
			chat = $('.chat[data-username="'+username+'"]');
		if($('.chat').length == 2 && $(chat).length == 0){
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
				var open = $(chat).data('open');
				if(open == false){
					$(chat).data('open',true).find('.chat-close').show();
				}
			}else{
				$('.chat-container').append("<div class='chat' data-username="+username+" data-open='true'><div><div class='chat-header'><a href='#'>"+username+"<span class='icon-close'></span></a></div><div class='chat-close chat-content'></div><div class='chat-close chat-footer'><form><input type='text' placeholder='Ecrire un message' class='form-control' /></form></div></div>");
			}
		}
		return false;
	});
}

function search_ajax(){
	$('#form_recherche input').val('');
	$('#form_recherche').submit(function(){
		var input = $(this).find('input'),
			form = $(this);
		$('#form_recherche .icon-loading').show();
		$.ajax({
			url: $(this).attr('action'),
			data: {"term" : $(this).find('input').val()},
			dataType: 'json',
			success: function(json){
				$('#form_recherche .icon-loading').hide();
				$('.searchFriends').html('');
				if(json.length > 3){
					$('#btn-more-search').css('display','inline-block').attr('href',json[0].urlMore);
				}else{
					$('#btn-more-search').hide();
				}
				$.each(json,function(index){
					if(index < 3){
						$('.searchFriends').append("<li><a href='"+json[index].url+"' title='"+json[index].username+"'><img src='/bundles/erazrsite/img/profil.png' alt='"+json[index].username+"' title='"+json[index].username+"' />"+json[index].username+"</a><a href='#' class='chat-btn' data-username='"+json[index].username+"'><span class='icon-comment'></span></a></li>");
					}
				});
			}
		});
	});
	$('#form_recherche').submit(function(){
		return false;
	});
}

function like_ajax(){
	$('.likes').click(function(){
		var el = $(this),
			as_liked = $(el).attr('data-asLiked'),
			href = $(el).attr('href');
		if(as_liked == "false"){ // N'a pas voté
			$.ajax({
				url: href,
				dataType: 'json',
				success: function(json){
					if(json[0].error == "false"){
						$('body').prepend('
							<ul class="flash">
							<li class="flash-error"><span class="flash-icon"><span class="icon-close"></span></span>
							'+json[0].error+'
							<a href="#" class="flash-close"><span class="icon-close"></span></a>
							</li>
							</ul>
						');
						flashMessage();
						$(el).attr('data-asliked',true).attr('data-original-title','Retirer le like').find('.icon-heart-empty').removeClass('.icon-heart-empty').addClass('.icon-heart');
					}else{
						$('body').prepend('
							<ul class="flash">
							<li class="flash-success"><span class="flash-icon"><span class="icon-success"></span></span>
							'+json[0].success+'
							<a href="#" class="flash-close"><span class="icon-close"></span></a>
							</li>
							</ul>
						');
						flashMessage();
						$(el).attr('data-asliked',false).attr('data-original-title','Ajouter un like').find('.icon-heart').removeClass('.icon-heart').addClass('.icon-heart-empty');
					}
				},
				error: function(yo, yop){
					console.log(yop);
				}
			});
		}else{ // A déjà voté
			$.ajax({
				url: href,
				dataType: 'json',
				success: function(json){
					console.log(json);
				},
				error: function(yo, yop){
					console.log(yop);
				}
			});
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