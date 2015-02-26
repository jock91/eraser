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
	$('.btn-facebook').click(function(){
		fb_login();
	});
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

// Facebook connect
window.fbAsyncInit = function(){
	FB.init({
		appId		: '514256792045832',
		//channelUrl	: '//yourdomain.com/channel.html',
		status		: true,
		xfbml		: true
	});
};
(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if(d.getElementById(id)){return;}
	js = d.createElement(s);js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function fb_login(){
	FB.getLoginStatus(function(response) {
		if(response.status === 'connected') {
			alert('Already connected, redirect to login page to create token.');
			document.location = "{{ url("hwi_oauth_service_redirect", {service: "facebook"}) }}";
		}else{
			FB.login(function(response){
				if(response.authResponse){
					document.location = "{{ url("hwi_oauth_service_redirect", {service: "facebook"}) }}";
				}else{
					alert('Cancelled.');
				}
			},{scope: 'email'});
		}
	});
}
if(window.location.hash == '#_=_'){
	window.location.hash = '';
	history.pushState('',document.title,window.location.pathname);
	e.preventDefault();
}