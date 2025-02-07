$(function(){



	//메뉴 활성화

	function activeGnb(idx,idx2){

		$('.siteMap .gnbList > ul > li').eq(idx).addClass('on');

		$('.siteMap .gnbList > ul > li').eq(idx).find('.subGnb li').eq(idx2).addClass('on');

	}



	/*

	$(function(){

		activeGnb(0,0);

	})

	*/



	if($(window).width() < 1280){

		$('.infoPop').addClass('active');

		setTimeout(function(){

			$('.infoPop').removeClass('active');

		},3000)

	}



	//서브페이지 하위메뉴

	$('.lnbSub .tit').on('click',function(e){

		$(this).parent().toggleClass('active');

		$(this).parents('.lnbSub').siblings().removeClass('active');

	})



	$('.infoPop').on('click',function(e){

		$('.infoPop').removeClass('active');

	})





	//모바일 퀵 확장

	$('.expBtn').on('click',function(e){

		$('.quickWrap').toggleClass('active');

	})





	//시설소개 더보기

	$('.btnMoreList a').on('click',function(e){

		$(this).parents('.facList').find('li').show();

		$(this).hide();

	})





	//모바일 상세 이미지 확대

	$('.btnZoom').on('click',function(e){

		let imgSrc = $(this).siblings('img').attr('src');

		window.open(imgSrc);

	})



	//모바일 gnb 서브 메뉴 열기

	$('.siteMap .gnbList > ul > li .tit a').on('click',function(e){

		if($(window).width() < 1280){

			e.preventDefault();

			$(this).toggleClass('active');

			$(this).parents('li').siblings().find('.tit a').removeClass('active');

			$(this).parents('li').find('.subGnb').stop().slideToggle('fast');

			$(this).parents('li').siblings().find('.subGnb').stop().slideUp('fast');

		}

	})





	//사이트맵 열기

	$('.btnMenu,.closeSite').on('click',function(){

		$('body').toggleClass('open');

	})



	//사업자 정보 토글

	$('.btnFold').on('click',function(){

		$('.footer .company').toggleClass('close');

	})



	//스크롤바

		

	$("#scrollBar").mCustomScrollbar({

		theme:"dark",

		axis:"x" 



	});

	

	$(".openSite .scroll").mCustomScrollbar({

		theme:"dark",

	});

		

	$('.openSite .tit').on('click',function(){

		$(this).parents('.openSite').toggleClass('active');

		$(this).parents('.openSite').siblings().removeClass('active');

	})





	

	//미디어체험 탭

	var $gnbItems = $('.expSec li');

	var $sections = $('.expSec .right .box');



	// 스크롤 이벤트 처리

	$(window).on('scroll', function() {

		var scrollPos = $(this).scrollTop();



		// 각 섹션에 대해 체크

		$sections.each(function(index) {

		  var $section = $(this);

		  var sectionOffsetTop = $section.offset().top;

		  var sectionHeight = $section.outerHeight();



		  // 섹션이 화면에 보이면 해당 GNB 항목에 클래스 추가

		  if (scrollPos >= sectionOffsetTop - 200 && scrollPos < sectionOffsetTop + sectionHeight - 200) {

			$gnbItems.removeClass('active');

			$gnbItems.eq(index).addClass('active');

		  }

		});

	});



	$('.expSec li').on('click',function(){

		var idx = $(this).index();

		var move = $('.expSec .right .box').eq(idx).offset().top - 200;

		$('html,body').stop().animate({scrollTop:move},500)

	})



	//미디어 교육 상세 탭

	$('.tabToggle ul li').on('click',function(){

		let idx = $(this).index();

		$(this).addClass('active').siblings().removeClass('active');

		$('.tabToggleCont .cont').eq(idx).show().siblings().hide();

	})





	//미디어 교육 탭

	$('.mainMedia .mainTit .tabList a').on('click',function(){

		let elName = $(this).attr('data-name');

		$('.mainMedia .tabCont #cont').removeClass().addClass(elName);

		$(this).addClass('active').siblings().removeClass('active');

	})





	//장비 공간 대여 탭

	$('.mainEq .mainTit .tabList a').on('click',function(){

		let elName = $(this).attr('data-name');

		$('.mainEq .tabCont contEq').removeClass().addClass(elName);

		$(this).addClass('active').siblings().removeClass('active');

	})

	$('.mainEq .mainTit .tabList a[data-name="eq"]').on('click', function() {
		$(".tabCont .eq").show();
		$(".tabCont .place").hide();
		$('a.btnMore.eq').show();
		$('a.btnMore.place').hide();
	});

	$('.mainEq .mainTit .tabList a[data-name="place"]').on('click', function() {
		$(".tabCont .place").show();
		$(".tabCont .eq").hide();
		$('a.btnMore.eq').hide();
		$('a.btnMore.place').show();
	});





	//모바일 셀렉트 이동

	$('.mobSelect select').change(function(){

		if(this.value != 'tit'){

			window.location.href = this.value

		}

	})





	//mainSlide

	mainSlide = new Swiper('.mainSlide', {

		/*

		effect: 'fade',

		fadeEffect: {

			crossFade: true 

		},

		*/

		autoplay: {

		   delay: 4000,

		 },

		speed:1000,

		spaceBetween:0,

		loop:true,

		pagination: {

			el: '.mainSlide .swiperPaging',

			clickable: true,

		},

		navigation: {

			nextEl: '.mainSlide .swiperNav .next',

			prevEl: '.mainSlide .swiperNav .prev',

		},



	});



	//mainVideo

	mainVideo = new Swiper('.videoSwiper', {

		autoplay: {

		   delay: 4000,

		 },

		spaceBetween:10,

		speed:1000,

		loop:true,

		pagination: {

			el: '.mainVideo .swiperPaging',

			clickable: true,

			type : 'bullets',

			renderBullet: function (index, className) {

				return '<span class="' + className + '">' + (index + 1) + "</span>";

			},

		},

	});



	//슬라이드시 현재 인덱스 출력

	if($('.controler').length > 0){

		$('.controler .total').text('0'+String(mainSlide.slides.length-2));

	}



	mainSlide.on('slideChange', function (e) {

		$('.count .state').text('0'+(Number(mainSlide.slides.eq(mainSlide.activeIndex).attr('data-swiper-slide-index')) + 1));

	});



	$('.controler .swiperNav .auto button').click(function(){

		if($(this).hasClass('pause')){

			mainSlide.autoplay.stop();

			$('.controler .swiperNav .auto button').removeClass().addClass('play');

		}else{

			mainSlide.autoplay.start();

			$('.controler .swiperNav .auto button').removeClass().addClass('pause');

		}



	})



	

	//FAQ

	$('.faqList .question').on('click',function(){

		$(this).parent().toggleClass('on');

		$(this).parents('li').find('.answer').slideToggle('fast');

		$(this).parents('li').siblings().find('.answer').slideUp('fast');

			

		$(this).parent().siblings().removeClass('on');

	})

	



	

	//위로가기

	$('.btnTop').on('click',function(){

		$('body,html').stop().animate({scrollTop:0},500)

	})

	



	//파일 선택

	$('.fileInputHidden').on('change',function(){

		$('#fileName').val($(this).val());

	})



	// 달력

	$.datepicker.regional.ko={closeText:"닫기",prevText:"이전달",nextText:"다음달",currentText:"오늘",monthNames:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],monthNamesShort:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],dayNames:["일","월","화","수","목","금","토"],dayNamesShort:["일","월","화","수","목","금","토"],dayNamesMin:["일","월","화","수","목","금","토"],weekHeader:"Wk",dateFormat:"yy-mm-dd",firstDay:0,isRTL:false,duration:200,showAnim:"show",showMonthAfterYear:true,yearSuffix:"년"};$.datepicker.setDefaults($.datepicker.regional.ko);



	var dates = $( "#st, #ed " ).datepicker({

		showOn: "both",

		buttonText:"날짜 선택",

		prevText: '이전 달',

		nextText: '다음 달',

		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],

		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],

		dayNames: ['일','월','화','수','목','금','토'],

		dayNamesShort: ['일','월','화','수','목','금','토'],

		dayNamesMin: ['일','월','화','수','목','금','토'],

		dateFormat: 'yy-mm-dd',

		showMonthAfterYear: true,

		showAnim:false,

		yearSuffix: '년',

		onSelect: function( selectedDate ) {

			var option = this.id == "st" ? "minDate" : "maxDate",

					instance = $( this ).data( "datepicker" ),

					date = $.datepicker.parseDate(

							instance.settings.dateFormat ||

									$.datepicker._defaults.dateFormat,

							selectedDate, instance.settings );

			dates.not( this ).datepicker( "option", option, date );

		}

	});



	var dates2 = $( "#st1" ).datepicker({

		showOn: "both",

		buttonText:"날짜 선택",

		prevText: '이전 달',

		nextText: '다음 달',

		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],

		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],

		dayNames: ['일','월','화','수','목','금','토'],

		dayNamesShort: ['일','월','화','수','목','금','토'],

		dayNamesMin: ['일','월','화','수','목','금','토'],

		dateFormat: 'yy-mm-dd',

		showMonthAfterYear: true,

		showAnim:false,

		yearSuffix: '년',

	});







	$("#agreeAll").click(function(){

		$(".agreeCheck").find( "input:checkbox" ).prop("checked" ,$(this).prop("checked") );

	})



	$(".agreeCheck input:checkbox").click(function(){



		if(!$(this).prop("checked")){

			$('#agreeAll').prop("checked",false);

		}



		var allCheck = 0;

			$(".agreeCheck").find( "input:checkbox" ).each(function(){

			if($(this).prop("checked")){

			allCheck++

		}

		})



		if(allCheck == $(".agreeCheck input:checkbox").length){

			$('#agreeAll').prop("checked",true);

		}

	})



});







//컨텐츠팝업-열기

function contentPop(el){

	$(el).show();

};



//컨텐츠팝업-닫기

function contentClose(){

	$('.contentPop').hide(); 

};