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
// Fixe des minutes si vide dans l'ajout de post
	add_post();
// Konami
	konami();
// Lol
	lol();

$('input[type="file"]').change(function(){
	var val = $(this).val();
	$('.file-text').html(val);
});

}

function resize(){
	fullHeight('aside','min');
}

/* ------------------------ */

function center(el){
	var elH = $(el).height(),
		elW = $(el).width();
	$(el).css({'margin-top':-elH/2,'margin-left':-elW/2});
}

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
	$(document).on('click','.chat-btn',function(){$('.flash').remove();
		$('body').prepend('
			<ul class="flash">
			<li class="flash-error"><span class="flash-icon"><span class="icon-close"></span></span>
			Fonctionnalité à venir
			<a href="#" class="flash-close"><span class="icon-close"></span></a>
			</li>
			</ul>
		');
		flashMessage();
/*
		var username = $(this).data('username'),
			chat = $('.chat[data-username="'+username+'"]');
		if($('.chat').length == 2 && $(chat).length == 0){
			$('.flash').remove();
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
*/
	});
}

function search_ajax(){
	$('#form_recherche input').val('');
	$('#form_recherche').submit(function(){
		var input = $(this).find('input'),
			form = $(this);
		$('#form_recherche .icon-loading').show();
		if($(input).val() == "Je t'aime" || $(input).val() == "je t'aime"){
			$('#btn-more-search').hide();
			setTimeout(function(){
				$('#form_recherche .icon-loading').hide().parents('form').find('input').val('');
				$('.searchFriends').html('').append('<li style="padding:1rem 2rem"><span class="icon-heart"></span> Erazr vous aime aussi</li>');
			}, 500);
		}else if($(input).val() == "Poney" || $(input).val() == "poney"){
			setTimeout(function(){
				$('#btn-more-search').hide();
				$('#form_recherche .icon-loading').hide().parents('form').find('input').val('');
				$('.searchFriends').html('').append('<li style="padding:1rem 2rem;margin-bottom:-1rem;height:5.6rem;">
					<img class="poney" src="https://ssl.gstatic.com/chat/babble/ee/ni.gif" />
					<img class="poney" src="https://ssl.gstatic.com/chat/babble/ee/lw.gif" />
					<img class="poney" src="https://ssl.gstatic.com/chat/babble/ee/rs.gif" />
					<img class="poney" src="https://ssl.gstatic.com/chat/babble/ee/cs.gif" />
				</li>');
				ponies();
			}, 500);
		}else if($(input).val() == "LOL" || $(input).val() == "lol"){
			setTimeout(function(){
				$('#form_recherche .icon-loading').hide().parents('form').find('input').val('');
				$('.lol').remove();
				$('body').append('
					<div class="lol">
						<img src="/bundles/erazrsite/img/lol.gif" />
					</div>
				');
				center('.lol img');
			}, 500);
		}else if($(input).val() == "Dead" || $(input).val() == "dead"){
			setTimeout(function(){
				$('#form_recherche .icon-loading').hide().parents('form').find('input').val('');
				$('.lol').remove();
				$('body').append('
					<div class="lol">
						<img src="/bundles/erazrsite/img/dead.gif" />
					</div>
				');
				center('.lol img');
			}, 500);
		}else if($(input).val() == "groin" || $(input).val() == "porc" || $(input).val() == "cochon" || $(input).val() == "Groin" || $(input).val() == "Porc" || $(input).val() == "Cochon"){
			setTimeout(function(){
				$('#form_recherche .icon-loading').hide().parents('form').find('input').val('');
				$('.lol').remove();
				$('body').append('
					<div class="lol">
						<img class="no-border" src="/bundles/erazrsite/img/groin-groin.svg" />
					</div>
				');
				center('.lol img');
			}, 500);
		}else{
			$.ajax({
				url: $(this).attr('action'),
				data: {"term" : $(this).find('input').val()},
				dataType: 'json',
				success: function(json){
					$('#form_recherche .icon-loading').hide().parents('form').find('input').val('');
					$('.searchFriends').html('');
					if(json.length > 3){
						$('#btn-more-search').css('display','inline-block').attr('href',json[0].urlMore);
					}else{
						$('#btn-more-search').hide();
					}
					if(json == ""){
						$('.searchFriends').append('<li style="padding:1rem 2rem">Aucun résultat</li>');
					}else{
						$.each(json,function(index){
							if(index < 3){
								$('.searchFriends').append("<li><a href='"+json[index].url+"' title='"+json[index].username+"'><img src='"+json[index].image+"' alt='"+json[index].username+"' title='"+json[index].username+"' class='img-profil' />"+json[index].username+"</a><a href='#' class='chat-btn' data-username='"+json[index].username+"'><span class='icon-comment'></span></a></li>");
							}
						});
					}
				}
			});
		}
	});
	$('#form_recherche').submit(function(){
		return false;
	});
}

function ponies(){
	$('.poney').each(function(index){
		var el		= $(this),
			index	= index+1,
			nb		= Math.ceil(Math.random() * 2),
			delay	= (Math.random() * 4500) + 4000;
		if(nb == 1){ // Tourner le poney vers la droite
			poney_left(el,delay);
		}else{ // Laisse le poney à gauche
			poney_right(el,delay);
		}
	});
	function poney_left(el){
		var delay	= (Math.random() * 4500) + 4000;
		$(el).css({'transform':'scale(-1,1)','left':'-62px'}).animate({'left':'100%'},delay, function(){
			poney_right(el);
		});
	}
	function poney_right(el){
		var delay	= (Math.random() * 4500) + 4000;
		$(el).css({'transform':'scale(1,1)','left':'100%'}).animate({'left':'-62px'},delay, function(){
			poney_left(el);
		});
	}
}

function like_ajax(){
	$(document).on('click','.likes',function(){
		var el = $(this),
			as_liked = $(el).attr('data-asLiked'),
			href = $(el).attr('href');
		if(as_liked == "false"){ // N'a pas voté
			$.ajax({
				url: href,
				dataType: 'json',
				success: function(json){
					if(json[0].success == "false"){
						$('.flash').remove();
						$('body').prepend('
							<ul class="flash">
							<li class="flash-error"><span class="flash-icon"><span class="icon-close"></span></span>
							Une erreur est survenue
							<a href="#" class="flash-close"><span class="icon-close"></span></a>
							</li>
							</ul>
						');
						flashMessage();
					}else{
						$('.flash').remove();
						$('body').prepend('
							<ul class="flash">
							<li class="flash-success"><span class="flash-icon"><span class="icon-success"></span></span>
							Vous aimez ce post
							<a href="#" class="flash-close"><span class="icon-close"></span></a>
							</li>
							</ul>
						');
						flashMessage();
						var like_nb = $(el).find('.like-nb').html();
						$(el).attr('data-asliked',true).attr('data-original-title','Retirer le like').find('.icon-heart-empty').removeClass('icon-heart-empty').addClass('icon-heart').next('.like-nb').html(parseInt(like_nb) + 1);
					}
				}
			});
		}else{ // A déjà voté
			$.ajax({
				url: href,
				dataType: 'json',
				success: function(json){
					if(json[0].success == "false"){
						$('.flash').remove();
						$('body').prepend('
							<ul class="flash">
							<li class="flash-error"><span class="flash-icon"><span class="icon-close"></span></span>
							Une erreur est survenue
							<a href="#" class="flash-close"><span class="icon-close"></span></a>
							</li>
							</ul>
						');
						flashMessage();
					}else{
						$('.flash').remove();
						$('body').prepend('
							<ul class="flash">
							<li class="flash-warning"><span class="flash-icon"><span class="icon-notif"></span></span>
							Vous n\'aimez plus ce post
							<a href="#" class="flash-close"><span class="icon-close"></span></a>
							</li>
							</ul>
						');
						flashMessage();
						var like_nb = $(el).find('.like-nb').html();
						$(el).attr('data-asliked',false).attr('data-original-title','Ajouter un like').find('.icon-heart').removeClass('icon-heart').addClass('icon-heart-empty').next('.like-nb').html(parseInt(like_nb) - 1);
					}
				}
			});
		}
		return false;
	});
}

function add_post(){
	$('form[name="erazr_bundle_sitebundle_post"]').submit(function(){
		if($('#erazr_bundle_sitebundle_post_timer_minute').val() == "" && $('#erazr_bundle_sitebundle_post_timer_hour').val() != ""){
			$('#erazr_bundle_sitebundle_post_timer_minute').val('00');
		}
	});
}

function konami(){
	var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
		t = [0, 41, 82, 123, 164, 205, 246, 287, 328, 369, 410],
		n = 0,
		a = true;
	$(document).keydown(function(e){
		if(e.keyCode === k[n++] && a === true){
			$('#floated').css({'width':t[n]+'px','margin-left':'-'+t[n]/2+'px'});
			if(n === k.length){
				$('#floated').animate({'width':0,'margin-left':0},200);
				$('#easterEgg').remove();
				var i = 0;
				while(i<=1){
					$('body').append("<div class='easterEgg egg-"+i+"'><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/lw.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ni.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/rs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/kr.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/lw.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ni.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/km.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/dg.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/tl.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mu.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/km.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/nk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mu.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/af.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/zf.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ib.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/bm.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/jl.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mt.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/nk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mt.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/af.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/km.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/nk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/lw.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/rr.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/cs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mu.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ib.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ib.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/af.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/bm.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mu.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/dl.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/rs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/cs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/af.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/lw.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/af.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/rs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mu.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/tj.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/km.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ib.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/tl.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/tj.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/zf.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/rs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mt.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/ni.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/tj.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/nk.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/cs.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mt.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/bm.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/bm.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/mu.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/kr.gif' /><img class='poney-konami' src='http://ssl.gstatic.com/chat/babble/ee/rr.gif' /></div>");
					i++;
				}
				pony_konami();
				return !1;
			}
		}else{
			n=0;
			$('#floated').animate({'width':0,'margin)left':0},200);
		}
	});
	function pony_konami(){
		$('.poney-konami').each(function(index){
			var el		= $(this),
				index	= index+1,
				nb		= Math.ceil(Math.random() * 2);
			if(nb == 1){ // Tourner le poney vers la droite
				poney_left_konami(el);
			}else{ // Laisse le poney à gauche
				poney_right_konami(el);
			}
		});
	}
	function poney_left_konami(el){
		var delay	= (Math.random() * 4500) + 7000,
			timeout = Math.floor(Math.random() * 20000) + 6;
		console.log(timeout);
		setTimeout(function(){
			$(el).css({'transform':'scale(-1,1)','left':'-62px'}).animate({'left':'100%'},delay, function(){
				poney_right_konami(el);
			});
		},timeout);
	}
	function poney_right_konami(el){
		var delay	= (Math.random() * 4500) + 7000,
			timeout = Math.floor(Math.random() * 20000) + 6;
		setTimeout(function(){
			$(el).css({'transform':'scale(1,1)','left':'100%'}).animate({'left':'-62px'},delay, function(){
				poney_left_konami(el);
			});
		},timeout);
	}
}

function lol(){
	$(document).on('click','.lol',function(){
		$(this).fadeOut(function(){$(this).remove();});
	});
}

/* ------------------------ */

// Fix facebook connect
	if(window.location.hash == '#_=_'){
		window.location.hash = '';
		history.pushState('', document.title, window.location.pathname);
		e.preventDefault();
	}