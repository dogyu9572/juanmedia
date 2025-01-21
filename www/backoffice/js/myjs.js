
jQuery(document).ready(function(){	
		// 마우스오버시 이미지 변환
	jQuery("img.rollover").mouseover(function(){
		jQuery(this).attr("src",jQuery(this).attr("src").replace(/^(.+)_off(\.[a-z]+)$/, "$1_on$2"));
	}).mouseout(function(){
		jQuery(this).attr("src",jQuery(this).attr("src").replace(/^(.+)_on(\.[a-z]+)$/, "$1_off$2"));
	});

	// 텝
	jQuery(".tab_content").hide();
	jQuery("ul.tabe>li:first").addClass("active").show(); 	
	jQuery(".tab_content:first").show();

	jQuery("ul.tabe>li").click(function(e) {
		e.preventDefault();

		jQuery("ul.tabe>li").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".tab_content").hide();		
		
		jQuery("ul.tabe>li").find('img').attr("src" ,function(iIndex,sSrc){
			return sSrc.replace('_on.gif', '_off.gif');
		});

		jQuery("ul.tabe>li.active").find('img').attr("src",function(iIndex,sSrc){
			return sSrc.replace('_off.gif', '_on.gif');
		});
		
		var activeTab = jQuery(this).find("a").attr("href");
		jQuery(activeTab).fadeIn();
		return false;
	});
	



});//end	