<!--

* 프로젝트 : AGSMobile V1.0
* (※ 본 프로젝트는 아이폰 및 안드로이드에서 이용하실 수 있으며 일반 웹페이지에서는 결제가 불가합니다.)

* 파일명 : AGS_pay.html
* 최종수정일자 : 2011/09/01

* 올더게이트 결제창을 호출합니다.

* Copyright AEGIS ENTERPRISE.Co.,Ltd. All rights reserved.

-->
<script language=javascript>

var _ua = window.navigator.userAgent.toLowerCase();

var browser = {
	model: _ua.match(/(samsung-sch-m490|sonyericssonx1i|ipod|iphone)/) ? _ua.match(/(samsung-sch-m490|sonyericssonx1i|ipod|iphone)/)[0] : "",
	skt : /msie/.test( _ua ) && /nate/.test( _ua ),
	lgt : /msie/.test( _ua ) && /([010|011|016|017|018|019]{3}\d{3,4}\d{4}$)/.test( _ua ),
	opera : (/opera/.test( _ua ) && /(ppc|skt)/.test(_ua)) || /opera mobi/.test( _ua ),
	ipod : /webkit/.test( _ua ) && /\(ipod/.test( _ua ) ,
	iphone : /webkit/.test( _ua ) && /\(iphone/.test( _ua ),
	lgtwv : /wv/.test( _ua ) && /lgtelecom/.test( _ua )
};

if(browser.opera) {
	document.write("<meta name=\"viewport\" content=\"user-scalable=no, initial-scale=0.75, maximum-scale=0.75, minimum-scale=0.75\" \/>");
} else if (browser.ipod || browser.iphone) {
	setTimeout(function() { if(window.pageYOffset == 0){ window.scrollTo(0, 1);} }, 100);
}

function Pay(form){
	try{
	if(parseInt(form.using_point.value) > <?=intval($nowPoint[nowpoint])?>){
		alert("사용하려는 적립금이 보유액보다 많습니다.");
		form.using_point.focus();
		return;
	}
	}catch(e){}
	if(Check_Common(form) == true){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 올더게이트 플러그인 설정값을 동적으로 적용하기 JavaScript 코드를 사용하고 있습니다.
		// 상점설정에 맞게 JavaScript 코드를 수정하여 사용하십시오.
		//
		// [1] 일반/무이자 결제여부
		// [2] 일반결제시 할부개월수
		// [3] 무이자결제시 할부개월수 설정
		// [4] 인증여부
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// [1] 일반/무이자 결제여부를 설정합니다.
		//
		// 할부판매의 경우 구매자가 이자수수료를 부담하는 것이 기본입니다. 그러나,
		// 상점과 올더게이트간의 별도 계약을 통해서 할부이자를 상점측에서 부담할 수 있습니다.
		// 이경우 구매자는 무이자 할부거래가 가능합니다.
		//
		// 예제)
		// 	(1) 일반결제로 사용할 경우
		// 	form.DeviId.value = "9000400001";
		//
		// 	(2) 무이자결제로 사용할 경우
		// 	form.DeviId.value = "9000400002";
		//
		// 	(3) 만약 결제 금액이 100,000원 미만일 경우 일반할부로 100,000원 이상일 경우 무이자할부로 사용할 경우
		// 	if(parseInt(form.Amt.value) < 100000)
		//		form.DeviId.value = "9000400001";
		// 	else
		//		form.DeviId.value = "9000400002";
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		form.DeviId.value = "9000400001";
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// [2] 일반 할부기간을 설정합니다.
		// 
		// 일반 할부기간은 2 ~ 12개월까지 가능합니다.
		// 0:일시불, 2:2개월, 3:3개월, ... , 12:12개월
		// 
		// 예제)
		// 	(1) 할부기간을 일시불만 가능하도록 사용할 경우
		// 	form.QuotaInf.value = "0";
		//
		// 	(2) 할부기간을 일시불 ~ 12개월까지 사용할 경우
		//		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
		//
		// 	(3) 결제금액이 일정범위안에 있을 경우에만 할부가 가능하게 할 경우
		// 	if((parseInt(form.Amt.value) >= 100000) || (parseInt(form.Amt.value) <= 200000))
		// 		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
		// 	else
		// 		form.QuotaInf.value = "0";
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//결제금액이 5만원 미만건을 할부결제로 요청할경우 결제실패
		if(parseInt(form.Amt.value) < 50000)
			form.QuotaInf.value = "0";
		else
			form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// [3] 무이자 할부기간을 설정합니다.
		// (일반결제인 경우에는 본 설정은 적용되지 않습니다.)
		// 
		// 무이자 할부기간은 2 ~ 12개월까지 가능하며, 
		// 올더게이트에서 제한한 할부 개월수까지만 설정해야 합니다.
		// 
		// 100:BC
		// 200:국민
		// 300:외환
		// 400:삼성
		// 500:신한
		// 800:현대
		// 900:롯데
		// 
		// 예제)
		// 	(1) 모든 할부거래를 무이자로 하고 싶을때에는 ALL로 설정
		// 	form.NointInf.value = "ALL";
		//
		// 	(2) 국민카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
		// 	form.NointInf.value = "200-2:3:4:5:6";
		//
		// 	(3) 외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
		// 	form.NointInf.value = "300-2:3:4:5:6";
		//
		// 	(4) 국민,외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
		// 	form.NointInf.value = "200-2:3:4:5:6,300-2:3:4:5:6";
		//	
		//	(5) 무이자 할부기간 설정을 하지 않을 경우에는 NONE로 설정
		//	form.NointInf.value = "NONE";
		//
		//	(6) 전카드사 특정개월수만 무이자를 하고 싶은경우(2:3:6개월)
		//	form.NointInf.value = "100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6";
		//
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(form.DeviId.value == "9000400002")
			form.NointInf.value = "100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6";

		form.submit();
	}
}

function Check_Common(form){
	if(form.StoreId.value == ""){
		alert("상점아이디를 입력하십시오.");
		return false;
	}
	else if(form.StoreNm.value == ""){
		alert("상점명을 입력하십시오.");
		return false;
	}
	else if(form.OrdNo.value == ""){
		alert("주문번호를 입력하십시오.");
		return false;
	}
	else if(form.ProdNm.value == ""){
		alert("상품명을 입력하십시오.");
		return false;
	}
	else if(form.Amt.value == ""){
		alert("금액을 입력하십시오.");
		return false;
	}
	else if(form.MallUrl.value == ""){
		alert("상점URL을 입력하십시오.");
		return false;
	}

	//올더게이트 - 샵모듈 탑재시 수정부분
	form.UserEmail.value = form.order_email.value;
	form.OrdNm.value = form.order_name.value;
	form.OrdPhone.value = form.order_phone1.value + "-" + form.order_phone2.value + "-" + form.order_phone3.value;
	form.OrdAddr.value = form.order_zip1.value + "-" + form.order_zip2.value + "---" + form.order_address.value + "---" + form.order_address_ext.value;
	form.RcpNm.value = form.order_name.value;
	form.RcpPhone.value = form.order_phone1.value + "-" + form.order_phone2.value + "-" + form.order_phone3.value;
	form.DlvAddr.value = form.ship_zip1.value + "-" + form.ship_zip2.value + "---" + form.ship_address.value + "---" + form.ship_address_ext.value;
	form.Remark.value = form.order_comment.value;
	form.RecNm.value = form.order_name.value;
	form.RecPhone.value = form.order_phone1.value + "-" + form.order_phone2.value + "-" + form.order_phone3.value;

	form.Column1.value = form.order_mobile1.value + "-" + form.order_mobile2.value + "-" + form.order_mobile3.value;
	form.Column2.value = form.ship_mobile1.value + "-" + form.ship_mobile2.value + "-" + form.ship_mobile3.value;

	try{
	form.Amt.value = parseInt(form.hiddenPayAmount.value) - parseInt(form.using_point.value);
	}catch(e){}

	//결제방법을 체크했는지 검사
	var obj = document.getElementsByName('pay_type'); 
	var objlength = obj.length;
	var objchecked = 0;
	var objcheckedval = "";
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objchecked++;
			objcheckedval = obj[i].value;
		}
	}

	form.Job.value = objcheckedval
	form.Column3.value = form.using_point.value + "---" + objcheckedval;

	return true;
}
</script>

<input type=hidden name=Job>
<input type=hidden name=RtnUrl value="http://<?=$_SITE["DOMAIN"]?>/module/shop/pg/agspay/AGS_pay_ing_mobile.php">
<input type=hidden name=CancelUrl value="http://<?=$_SITE["DOMAIN"]?>/shop.php?goPage=OrderMobile">

<input type=hidden name=StoreId maxlength=20 value="<?=$_SITE["SHOP"]["PG"]["MALLID"]?>">
<input type=hidden name=OrdNo maxlength=40 value="<?=$order_no?>">
<input type=hidden name=Amt maxlength=12 value="<?=$payPrice?>">
<input type=hidden name=StoreNm value="<?=$_SITE["NAME"]?>">
<input type=hidden name=ProdNm maxlength=300 value="<?=stripslashes($order_summary)?>">
<input type=hidden name=MallUrl value="http://<?=$_SITE["DOMAIN"]?>">
<input type=hidden name=UserEmail maxlength=50 value="">
<input type=hidden name=UserId maxlength=20 value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
<input type=hidden name=OrdNm maxlength=40 value="">
<input type=hidden name=OrdPhone maxlength=21 value="">
<input type=hidden name=OrdAddr maxlength=100 value="">
<input type=hidden name=RcpNm maxlength=40 value="">
<input type=hidden name=RcpPhone maxlength=21 value="">
<input type=hidden name=RecNm maxlength=40 value="">
<input type=hidden name=RecPhone maxlength=21 value="">
<input type=hidden name=DlvAddr maxlength=100 value="">
<input type=hidden name=Remark maxlength=350 value="">
<input type=hidden name=MallPage maxlength=350 value="<?=$_SERVER['REQUEST_URI']?>">
<input type=hidden name=VIRTUAL_DEPODT value="">	<!-- 가상계좌입금예정일 -->

<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->

<input type=hidden name=Column1 value="">  <!-- 임시 필드1 -->
<input type=hidden name=Column2 value="">  <!-- 임시 필드2 -->
<input type=hidden name=Column3 value="">  <!-- 임시 필드3 -->

<input type=hidden name=HP_SUBID value="<?=$_SITE["SHOP"]["PG"]["HP_SUBID"]?>">	
<input type=hidden name=HP_ID value="<?=$_SITE["SHOP"]["PG"]["HP_ID"]?>">
<input type=hidden name=HP_PWD value="<?=$_SITE["SHOP"]["PG"]["HP_PWD"]?>">
<input type=hidden name=ProdCode value="<?=$_SITE["SHOP"]["PG"]["ProdCode"]?>">
<input type=hidden name=HP_UNITType value="<?=$_SITE["SHOP"]["PG"]["HP_UNITType"]?>">

<input type=hidden name=DeviId value="">			<!-- 단말기아이디 -->
<input type=hidden name=QuotaInf value="0">			<!-- 할부개월설정변수 -->
<input type=hidden name=NointInf value="NONE">		<!-- 무이자할부개월설정변수 -->

<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->
<!-- 올더게이트 결제 폼 -->
