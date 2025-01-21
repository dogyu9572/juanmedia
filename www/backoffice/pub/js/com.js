$(document).ready(function(){
//ie_check
	var ua = window.navigator.userAgent;
	if(ua.indexOf('MSIE') > 0 || ua.indexOf('Trident/') > 0){ //윈도우 10까지는 MSIE 윈도우 11부터는 Trident/를 사용한다.
		document.body.className="ie";
	}
//헤더
	$(window).scroll(function() {
		if ($(window).scrollTop() > 100) {
			$(".header").addClass("fixed");
		} else {
			$(".header").removeClass("fixed");
		}
	});
//좌측 메뉴
	$(".aside .menu dt").click(function(){
		$(this).next("dd").stop(false,true).slideToggle().parent().stop(false,true).toggleClass("on").siblings().removeClass("on").children("dd").slideUp("fast");
	});
	$(".aside .btn_aside").click(function(){
		$(".aside").stop(false,true).toggleClass("opcl");
		$(".container").stop(false,true).toggleClass("full");
	});
//모바일
	$(".btn_menu").click(function(){
		$("html,body").stop(false,true).toggleClass("over_h");
		$(".header").stop(false,true).toggleClass("on");
	});
	$(".header .gnb li .mo_vw").click(function(){
		$(this).next(".snb").stop(false,true).slideToggle("fast").parent().stop(false,true).toggleClass("on").siblings().removeClass("on").children(".snb").slideUp("fast");
	});
//브라우저 사이즈
	let vh = window.innerHeight * 0.01; 
	document.documentElement.style.setProperty('--vh', `${vh}px`);
//화면 리사이즈시 변경 
	window.addEventListener('resize', () => {
		let vh = window.innerHeight * 0.01; 
		document.documentElement.style.setProperty('--vh', `${vh}px`);
	});
	window.addEventListener('touchend', () => {
		let vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', `${vh}px`);
	});
//셀렉트박스 커스텀
		$('select').niceSelect();
});