<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<?
	// 클라이언트 ip 가져오기
	$ip = $_SERVER['REMOTE_ADDR'];
?>
<title>스마트로::인터넷결제</title>

<script language="javascript">
<!--
function goCancelCard() {
	var formNm = document.tranMgr;
	
	// TID validation
	if(formNm.TID.value == "") {
		alert("TID를 확인하세요.");
		return false;
	} else if(formNm.TID.value.length > 30 || formNm.TID.value.length < 30) {
		alert("TID 길이를 확인하세요.");
		return false;
	}
	// 취소금액
	if(formNm.CancelAmt.value == "") {
		alert("금액을 입력하세요.");
		return false;
	} else if(formNm.CancelAmt.value.length > 12 ) {
		alert("금액 입력 길이 초과.");
		return false;
	}
	var PartialValue = "";
	// 부분취소여부 체크 - 신용카드, 계좌이체 부분취소 가능
	for(var idx = 0 ; idx < formNm.PartialCancelCode.length ; idx++){
		if(formNm.PartialCancelCode[idx].checked){
			PartialValue = formNm.PartialCancelCode[idx].value;
			break;
		}
	}
	
	if(PartialValue == '1'){
		if(formNm.TID.value.substring(10,12) != '01' &&  formNm.TID.value.substring(10,12) != '02' &&  formNm.TID.value.substring(10,12) != '03'){
			alert("신용카드결제, 계좌이체, 가상계좌만 부분취소/부분환불이 가능합니다");
			return false;
		}
	}
	
	formNm.submit();
	return true;
}

-->
</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>

<link href="./css/Confirmation.css" rel="stylesheet" type="text/css" />
<link href="./css/ims.css" rel="stylesheet" type="text/css" />
<link href="./css/mms.css" rel="stylesheet" type="text/css" />
<link href="./css/payment.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body onbeforeunload="" oncontextmenu='return false' ondragstart='return false' onselectstart='return false'>
<form name="tranMgr" method="post" action="https://pay.smilepay.co.kr/cancel/payCancelProcess.jsp">
<table width="500" border="0" cellpadding="0" cellspacing="0" bgcolor="e5e5e5">
  <tr>
    <td width="153" rowspan="2" valign="top"><img src="./images/logo_c.gif" width="153" height="88" /></td>
    <td width="477" height="31" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="../images/bottom01.jpg">
      <tr>
        <td><img src="./images/no.gif" width="5" height="3" /></td>
      </tr>
    </table></td>
    <td width="20" rowspan="2" valign="top"><img src="./images/no.gif" width="20" height="20" /></td>
  </tr>
  <tr>
    <td valign="bottom"><table width="298" height="73" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="298" valign="bottom"><div align="right"><img src="./images/title03.gif" /></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"><img src="./images/no.gif" width="20" height="20" /></td>
    <td width="610">&nbsp;</td>
    <td width="20"><img src="./images/no.gif" width="20" height="20" /></td>
  </tr>
  <tr>
    <td rowspan="5">&nbsp;</td>
    <td><div align="center"><span class="style3">[ 취소정보를 확인하십시오]</span></div></td>
    <td rowspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td height="15">&nbsp;</td>
  </tr>
  <tr>
    <td height="15"><table width="100%" border="0" cellpadding="0" cellspacing="1" background="./images/tb_patt03.jpg" bgcolor="#E2E2E2">
      <tr>
        <td width="103" height="30" bgcolor="#D9CDBF"><div align="center">TID</div></td>
        <td width="12" height="30" valign="middle">&nbsp;</td>
        <td width="350" height="30" valign="middle"><input name="TID" type="text" class="input" id="TID" value="" size="30" maxlength="30" /></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">취소패스워드</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong>
          <input name="Cancelpw" type="password" class="input" id="Cancelpw" /> 
          * 데모시 미입력</strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">취소금액</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><input name="CancelAmt" type="text" class="input " id="CancelAmt" value="" /></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">취소사유</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><input name="CancelMSG" type="text" class="input " id="CancelMSG" value="고객요청" /></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">부분취소 여부<span class="style3"></span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong>
          <input type="radio" name="PartialCancelCode" id="PartialCancelCode" value="0" checked="checked"/>
          전체취소 
          <input type="radio" name="PartialCancelCode" id="PartialCancelCode" value="1" />
          부분취소</strong></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">
      <table width="150" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="./images/btn_confirm.gif" width="63" height="23" onClick="return goCancelCard();"/></td>
          <td><div align="right"><img src="../images/btn_1.gif" width="65" height="23" onClick="javascript:document.tranMgr.reset();"/></div></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"><img src="./images/no.gif" width="20" height="50" /></td>
    <td width="610" valign="bottom"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="./images/bottom01.jpg">
      <tr>
        <td><img src="./images/no.gif" width="5" height="5" /></td>
      </tr>
    </table></td>
    <td width="20"><img src="./images/no.gif" width="20" height="50" /></td>
  </tr>
</table>
<p>&nbsp;</p>
<input type="hidden" name="cc_ip" size="20" value="<?=$ip?>"/>
<input type="hidden" name="EncodingType" value="euckr"/><!--utf8 -->
<input type="hidden" name="FORWARD" value="Y" />
<input type="hidden" name="NoPop" value="Y" />
<input type="hidden" name="ReturnURL" value="http://localhost/returnCancelPay.asp" />
</form>
</body>
</html>
