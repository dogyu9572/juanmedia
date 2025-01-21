var ediDate;
var merchantKey;
var payActionUrl;

function setPayActionUrl(x) {
	payActionUrl = x;
}

/**
 * 지불방법에 따른 UI 분류 
 */
function goUI(payType) {	
	var payFormNm = document.payForm;
	
	if(payType == '0') { // 전체
		payFormNm.payType.value	= '0';
	} else if(payType == '1') { // 신용카드
		payFormNm.payType.value	= '1';
	} else if(payType == '2') { 
		payFormNm.payType.value	= '2';
	} else if(payType == '3') {
		payFormNm.payType.value	= '3';
	} else if(payType == '4') {
		payFormNm.payType.value	= '4';
	} else if(payType == '5') {
		payFormNm.payType.value	= '5';
	} else if(payType == '6') { // 계좌이체
		payFormNm.payType.value	= '6';
	} else if(payType == '7') { // 가상계좌
		payFormNm.payType.value	= '7';
	} else if(payType == '8') { // 신용카드 + 계좌이체
		payFormNm.payType.value	= '8';
	} else if(payType == '9') { //휴대폰결제
		payFormNm.payType.value	= '9';
	}
}


/**
 * 카드결제
 */
function goSelectCard() {
	var payFormNm	= document.payForm;
	payFormNm.PayMethod.value = "CARD";
	
	// 결제수단
	goUI('1');	
	// 결제 전송
	goPay();
}

/**
 * 실시간 계좌이체
 */
function goSelectBank() {
	var payFormNm	= document.payForm;
	payFormNm.PayMethod.value = "BANK";
	
	// 결제수단 
	goUI('6');
	// 결제 전송
	goPay();
}

/**
 * 가상계좌
 */
function goSelectVBank() {
	var payFormNm	= document.payForm;
	payFormNm.PayMethod.value = "VBANK";
	
	// 결제수단
	goUI('7');
	// 결제 전송
	goPay();
}

/**
 * 결제 전송
 */
function goPay() {

	var formNm		= document.tranMgr;
	var payFormNm	= document.payForm;
	
	var BuyerAuthNum    = formNm.BuyerAuthNum.value;
	var BuyerTel        = formNm.BuyerTel.value;
	var BuyerEmail      = formNm.BuyerEmail.value;
	var ParentEmail     = formNm.ParentEmail.value;
	var BrowserType     = '';	

    // 사업자 번호 체크
    if(BuyerAuthNum.length == 10) {
        if(!isBusiNoByValue(BuyerAuthNum)) {
            alert("사업자번호를 확인하세요.");
            return;
        }
    }
    // 주민번호 체크
    else if(BuyerAuthNum.length == 13) {
        var juminNo1 = BuyerAuthNum.substring(0, 6);
        var juminNo2 = BuyerAuthNum.substring(6, 13);
        
        if(!isJuminNo(juminNo1, juminNo2)) {
            alert("주민등록번호를 확인하세요.");
	        return;
	    }
    }
	// 메일주소 검증
	if(BuyerEmail == '') {
	    alert("구매자 메일주소를 입력해 주세요.");
	    return;
	}
	else {
		if(!EmailCheck(BuyerEmail)) {
			alert("구매자메일주소가 형식에 맞지 않습니다.");
			return;
		}
	}
	if(ParentEmail != '') {	
		if(!EmailCheck(ParentEmail)) {
			alert("보호자메일주소가 형식에 맞지 않습니다.");
			return;
		}
	}	
	
	// 주문번호 특수문자 체크
	if(isSpecial(formNm.Moid.value)) {
		alert("주문번호에는 특수문자가 허용되지 않습니다.");
		return;
	}
	
	if( navigator.appName.indexOf("Microsoft") > -1 ) {
		if( navigator.appVersion.indexOf("MSIE 7") > -1 ) {
			BrowserType = "MSIE 7";
		}
		else if( navigator.appVersion.indexOf(navigator.appVersion.indexOf( "MSIE 6" ) > -1) ) {
			BrowserType = "MSIE 6";
		}
	}

	payFormNm.EncryptData.value		= formNm.EncryptData.value;
	payFormNm.GoodsCnt.value		= formNm.GoodsCnt.value;
	payFormNm.GoodsName.value		= formNm.GoodsName.value;
	payFormNm.Amt.value				= formNm.Amt.value;	
	payFormNm.GoodsURL.value		= formNm.GoodsURL.value;
	payFormNm.Moid.value			= formNm.Moid.value;
	payFormNm.MID.value				= formNm.MID.value;
	payFormNm.ReturnURL.value		= formNm.ReturnURL.value;
	payFormNm.ResultYN.value		= formNm.ResultYN.value;
	payFormNm.RetryURL.value		= formNm.RetryURL.value;
	payFormNm.mallUserID.value		= formNm.mallUserID.value;
	payFormNm.BuyerName.value		= formNm.BuyerName.value;
	payFormNm.BuyerAuthNum.value	= formNm.BuyerAuthNum.value;
	payFormNm.BuyerTel.value		= formNm.BuyerTel.value;
	payFormNm.BuyerEmail.value		= formNm.BuyerEmail.value;
	payFormNm.ParentEmail.value		= formNm.ParentEmail.value;
	payFormNm.BuyerAddr.value		= formNm.BuyerAddr.value;
	payFormNm.BuyerPostNo.value		= formNm.BuyerPostNo.value;
	payFormNm.MallIP.value			= formNm.MallIP.value;
	payFormNm.VbankExpDate.value	= formNm.VbankExpDate.value;
	payFormNm.BrowserType.value		= BrowserType;
	payFormNm.MallReserved.value	= formNm.MallReserved.value;
	payFormNm.SUB_ID.value	= formNm.SUB_ID.value;
	payFormNm.GoodsCl.value	= formNm.GoodsCl.value;
	payFormNm.EncodingType.value	= formNm.EncodingType.value;
	payFormNm.OfferPeriod.value	= formNm.OfferPeriod.value;
	payFormNm.OpenType.value	= formNm.OpenType.value;
	payFormNm.SocketYN.value = formNm.SocketYN.value;
	payFormNm.EncodeParameters.value = formNm.EncodeParameters.value;
	payFormNm.SkinColor.value = formNm.SkinColor.value;




	payFormNm.action = payActionUrl + '/interfaceURL.jsp';

	//CharterSet Setting ----------------------------
	var encodingType = "EUC-KR";//UTF-8
  	if(getVersionOfIE() != 'N/A')
  		document.charset = encodingType;//ie
  	else
  		payFormNm.charset = encodingType;//else
	//-----------------------------------------------

	if(payFormNm.FORWARD.value == 'Y'){
		var left = (screen.Width - 545)/2;
		var top = (screen.Height - 573)/2;
		var winopts= "left="+left+",top="+top+",width=545,height=573,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no";
		var win =  window.open("", "payWindow", winopts);	
		payFormNm.target = "payWindow";
		payFormNm.submit();
		
	}else{
		payFormNm.target = "payFrame";
		payFormNm.submit();
		payFormNm.PayMethod.value = "";
		payFormNm.payType.value = "";
	}
}

/**
 * IE버전 확인
 */
function getVersionOfIE() 
{ 
	 var word; 
	 var version = "N/A"; 

	 var agent = navigator.userAgent.toLowerCase(); 
	 var name = navigator.appName; 

	 // IE old version ( IE 10 or Lower ) 
	 if ( name == "Microsoft Internet Explorer" ) 
	 {
		 word = "msie "; 
	 }
	 else 
	 { 
		 // IE 11 
		 if ( agent.search("trident") > -1 ) word = "trident/.*rv:"; 

		 // IE 12  ( Microsoft Edge ) 
		 else if ( agent.search("edge/") > -1 ) word = "edge/"; 
	 } 

	 var reg = new RegExp( word + "([0-9]{1,})(\\.{0,}[0-9]{0,1})" ); 

	 if ( reg.exec( agent ) != null  ) 
		 version = RegExp.$1 + RegExp.$2; 

	 return version; 
}

/**
 * 연동페이지
 * 1.상점별 결제수단 파악해서 쿠키에 넣어줄지 결정
 */
function goInterface() {

    //var selectType = selectedBoxValue(document.tranMgr.selectType);
    
	var selectType = document.tranMgr.selectType.value;

	var payFormNm	= document.payForm;
	payFormNm.PayMethod.value = selectType;

	if(selectType == '') { // 전체
		goUI('0');
	} else if(selectType == 'CARD') {
	    goUI('1');
	} else if(selectType == 'BANK') {
	    goUI('6');
	} else if(selectType == 'VBANK') {
	    goUI('7');
	} else if(selectType == 'CARD+BANK') {
	    goUI('8');
	    payFormNm.PayMethod.value = 'CARD';
	} else if(selectType == 'CELLPHONE'){
		goUI('9');
		 payFormNm.PayMethod.value = 'CELLPHONE';
	}
    
	// 결제수단
	goPay();
	
	return false;
}

/****************************************************************************************
 * objName		- select box Object
 * description	- select box 값 얻어오기
 ****************************************************************************************/
function selectedBoxValue(objName) {
	return objName[objName.selectedIndex].value;
}

/****************************************************************************************
 * 특수 문자 체크
 ****************************************************************************************/
function isSpecial(checkStr) {
	var checkOK = "~`':;{}[]<>,.!@#$%^&*()_+|\\/?";

	for (i = 0;  i < checkStr.length;  i++)	{
		ch = checkStr.charAt(i);
		for (j = 0;  j < checkOK.length;  j++) {
			if (ch == checkOK.charAt(j)) {return true; break;}
		}
	}
	return false;
}

/****************************************************************************************
 * Email Check
 ****************************************************************************************/
function EmailCheck(arg_v) {
	var	vValue = "";

	if(arg_v.indexOf("@") < 0) return false;

	for(var i = 0; i < arg_v.length; i++) {
		vValue = arg_v.charAt(i);

		if (AlphaCheck(vValue) == false  && NumberCheck(vValue) == false && EmailSpecialCheck(vValue) == false )
			return false;
	}
	return true;
}

/****************************************************************************************
 * 영문 판별
 ****************************************************************************************/
function AlphaCheck(arg_v) {
	var alphaStr = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	if ( alphaStr.indexOf(arg_v) < 0 )
		return false;
	else
		return true;
}

/****************************************************************************************
 * 숫자 판별
 ****************************************************************************************/
function NumberCheck(arg_v) {
	var numStr = "0123456789";

	if ( numStr.indexOf(arg_v) < 0 )
		return false;
	else
		return true;
}

/****************************************************************************************
 * Email 특수 문자 체크
 ****************************************************************************************/
function EmailSpecialCheck(arg_v) {
	var SpecialStr = "_-@.";

	if ( SpecialStr.indexOf(arg_v) < 0 )
		return false;
	else
		return true;
}

/****************************************************************************************
 * objName		- 라디오버튼 Object
 * description	- 라디오버튼 값 얻어오기
 ****************************************************************************************/
function checkedRadioButtonValue(objName) {
	var radioVal = '';
	var radioObj = document.all(objName);
	
	if(radioObj.length == null) {
		if(radioObj.checked){
			radioVal = radioObj.value;
		}
	}
	else {
		for(i = 0; i < radioObj.length; i++) {
			if(radioObj[i].checked) {
				radioVal = radioObj[i].value;
				break;
			}
		}
	}
	return radioVal;
}

/****************************************************************************************
 * 입력필드(사용자가 키보드를 처서 입력하는)의 입력값이 숫자만 들어가도록 할 때 사용된다.
 * 사용예 : <input type="text" name="text" onKeyUp="javascript:numOnly(this,document.frm,true);">
 * 여기서 this는 오브젝트를 뜻하므로 그냥 사용하면 되고, document 다음의 frm 대신에
 * 자신이 사용한 form 이름을 적어준다.
 * 마지막 파라미터로 true,false 를 줄 수 있는데 true로 주면 금액등에 쓰이는 3자리마다 콤마를
 * false 로 주면 그냥 숫자만 입력하게 한다.
 ****************************************************************************************/
function numOnly(obj, frm, isCash) {
	if (event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39) return;
	var returnValue = "";
	for (var i = 0; i < obj.value.length; i++){
		if (obj.value.charAt(i) >= "0" && obj.value.charAt(i) <= "9"){
			returnValue += obj.value.charAt(i);
		}else{
			returnValue += "";
		}
	}

	if (isCash){
		obj.value = cashReturn(returnValue);
		return;
	}
	obj.focus();
	obj.value = returnValue;
}

/****************************************************************************************
 * 금액을 위한 함수, 코더들은 이 function을 직접 부를 필요 없다. numOnly함수에 마지막
 * 파라미터를 true로 주고 numOnly를 부른다.
 ****************************************************************************************/
function cashReturn(numValue) {
	var cashReturn = "";
	for (var i = numValue.length-1; i >= 0; i--){
		cashReturn = numValue.charAt(i) + cashReturn;
		if (i != 0 && i%3 == numValue.length%3) cashReturn = "," + cashReturn;
	}

	return cashReturn;
}

/****************************************************************************************
 * 사업자등록번호 체크
 ****************************************************************************************/
function isBusiNoByValue(strNo) { 

	var sum = 0;
	var getlist =new Array(10);
	var chkvalue =new Array("1","3","7","1","3","7","1","3","5"); 

	for ( var i = 0; i < 10; i++ ) {
		getlist[i] = strNo.substring(i, i+1); 
	}

	for ( var i = 0; i < 9; i++ ) {
		sum += getlist[i]*chkvalue[i];
	}
	
	sum = sum + parseInt((getlist[8]*5)/10); 
	sidliy = sum%10;
	sidchk = 0;

	if ( sidliy != 0 ) {
		sidchk = 10 - sidliy; 
	}
	else {
		sidchk = 0; 
	}
	
	if ( sidchk != getlist[9] ) { 
		return false;
	}
		return true;
}

/****************************************************************************************
 * 주민번호 정합성 체크
 ****************************************************************************************/
function isJuminNo(juminNo1, juminNo2) {
	var f1 = juminNo1.substring(0, 1);
	var f2 = juminNo1.substring(1, 2);
	var f3 = juminNo1.substring(2, 3);
	var f4 = juminNo1.substring(3, 4);
	var f5 = juminNo1.substring(4, 5);
	var f6 = juminNo1.substring(5, 6);	
	
	var l1 = juminNo2.substring(0, 1);
	var l2 = juminNo2.substring(1, 2);
	var l3 = juminNo2.substring(2, 3);
	var l4 = juminNo2.substring(3, 4);
	var l5 = juminNo2.substring(4, 5);
	var l6 = juminNo2.substring(5, 6);
	var l7 = juminNo2.substring(6, 7);
	
	var sum = f1 * 2 + f2 * 3 + f3 * 4 + f4 * 5 + f5 * 6 + f6 * 7;
	sum = sum + l1 * 8 + l2 * 9 + l3 * 2 + l4 * 3 + l5 * 4 + l6 * 5;
	sum = sum % 11;
	sum = 11 - sum;
	sum = sum % 10;
	
	if (sum != l7) {return false;}
	
	return true;
}
