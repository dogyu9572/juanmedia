<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href='./css/Confirmation.css' rel="stylesheet" type="text/css">
<link href='./css/ims.css' rel="stylesheet" type="text/css">
<link href='./css/mms.css' rel="stylesheet" type="text/css">
<link href='./css/payment.css' rel="stylesheet" type="text/css">
<style type="text/css">
<!--

.style9 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<?php

	$VbankExpDate =  date("Ymd", strtotime($day."1 day"));
	// 클라이언트 ip 가져오기
	$ip = $_SERVER['REMOTE_ADDR'];
	// 서버 ip 가져오기
	$server_ip = $_SERVER['SERVER_NAME'];	
	// 전문생성일시
	$ediDate = date("YmdHis");
	// 상점서명키 (꼭 해당 상점키로 바꿔주세요)
	$merchantKey = "0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow==";
	$MID = "SMTPAY001m";
	$goodsAmt = "1004";
	// 웹링크 결제 서버 IP 세팅
	$payActionUrl = "https://tpay.smilepay.co.kr";
	//$payActionUrl = "https://pay.smilepay.co.kr";
	$encryptData = base64_encode(md5($ediDate.$MID.$goodsAmt.$merchantKey));
?>
<title>스마트로::인터넷결제</title>
<script language='javascript' src='./js/incMerchant.js'></script>
<?php
    echo("<script language=javascript>setPayActionUrl(\"$payActionUrl\");</script>");
?>
</head>
<body oncontextmenu='return false' ondragstart='' onselectstart='' style="overflow:scroll">
<form name="tranMgr" method="post" action="">
<input type="hidden" name="GoodsURL"/>
<input type="hidden" name="EncryptData" value=<?php echo $encryptData ?>>
<table width="650" border="0" cellpadding="0" cellspacing="0" bgcolor="e5e5e5">
  <tr>
    <td width="153" rowspan="2" valign="top"><img src="./images/logo_c.gif" width="153" height="88" /></td>
    <td width="477" height="31" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="./images/bottom01.jpg">
      <tr>
        <td><img src="./images/no.gif" width="5" height="3" /></td>
      </tr>
    </table></td>
    <td width="20" rowspan="2" valign="top"><img src="./images/no.gif" width="20" height="20" /></td>
  </tr>
  <tr>
    <td valign="bottom"><table width="314" height="73" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="314" valign="bottom"><div align="right"><img src="./images/title01.gif" /></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"><img src="./images/no.gif" width="20" height="20" /></td>
    <td width="610">&nbsp;</td>
    <td width="20"><img src="./images/no.gif" width="20" height="20" /></td>
  </tr>
  <tr>
    <td rowspan="3">&nbsp;</td>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" background="./images/tb_patt03.jpg" bgcolor="#E2E2E2">
      <tr>
        <td width="151" height="30" bgcolor="#D9CDBF"><div align="center">결제수단</div></td>
        <td width="12" height="30" valign="middle">&nbsp;</td>
        <td width="423" height="30" valign="middle">
        	<select name="selectType" class="input" >
						<option value="">[선택]</option>
						<option value="CARD">[신용카드]</option>
						<option value="BANK">[계좌이체]</option>
						<option value="VBANK">[가상계좌]</option>
						<option value="CELLPHONE">[휴대폰결제]</option>
					  <option value="CLGIFT">[문화상품권]</option>							
					</select>
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상품갯수</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="GoodsCnt" size="20" class="input" value="1" onKeyUp="javascript:numOnly(this,document.tranMgr,false);">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상품명<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="GoodsName" size="20" class="input" value="곰돌이">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상품가격<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="Amt" size="20" value="<?php echo $goodsAmt ?>" class="input" onKeyUp="javascript:numOnly(this,document.tranMgr,false);">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상품주문번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="Moid" size="20" class="input" value="mnoid1234567890">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">회원사아이디<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="MID" size="20" class="input" value="<?php echo $MID ?>">
        </td>
      </tr>
       <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">서브몰아이디<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="SUB_ID" size="50" class="input" value="">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결제결과전송 URL<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="ReturnURL" size="50" class="input" value="http://127.0.0.1:8001/returnPay.php">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결제결과창유무</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="ResultYN" class="input" size="50" value="Y">  
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결제결과 RETRY URL<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="RetryURL" class="input" size="50" value="http://127.0.0.1:8001/inform.php">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">회원사고객 ID</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="mallUserID" class="input" size="20" value="mn_id">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자명</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="BuyerName" class="input" size="20" value="mn_구매자명">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자인증번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="BuyerAuthNum" class="input" size="20" maxlength="13" onKeyUp="javascript:numOnly(this,document.tranMgr,false);">
          (-)없이 입력:주민번호,사업자번호</td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자연락처</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="BuyerTel" class="input" size="20" value="0212345678">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자메일주소<span class="style9">(*)</span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="BuyerEmail" class="input" size="20" value="smpark@smartro.co.kr">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">보호자메일주소</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="ParentEmail" class="input" size="20">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">배송지주소</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="BuyerAddr" class="input" size="50" value="서울시 금천구 가산동 245-9">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">우편번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="BuyerPostNo" class="input" size="6" value="135914">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">Mail IP</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="MallIP" class="input" size="20" value="<?php echo $server_ip ?>">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상점예비정보</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="MallReserved" class="input" size="20" value="MallReserved">
        </td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">가상계좌입금기한</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle">
        	<input name="VbankExpDate" class="input" value="<?php echo $VbankExpDate ?>">
        </td>
      </tr>
      
       <tr>
        <td width="151" height="30" bgcolor="#D9CDBF"><div align="center">인코딩타입</div></td>
        <td width="12" height="30" valign="middle">&nbsp;</td>
        <td width="423" height="30" valign="middle">
        	<select name="EncodingType" class="input" >
						<option value="">[선택]</option>
						<option value="euckr">[EUC-KR]</option>
						<option value="utf8">[UTF-8]</option>
						
					</select>
        </td>
      </tr>
	  	<tr>
		<td width="151" height="30" bgcolor="#D9CDBF">
		<div align="center">결제구분</div></td>
		<td width="12" height="30" valign="middle">&nbsp;</td>
		<td width="423" height="30" valign="middle">
		<select name="GoodsCl" class="input">
		<option value="1" selected="selected">실물</option>
		<option value="0">컨텐츠</option>
		</select></td>
	 </tr>
	 <tr>
		<td width="151" height="30" bgcolor="#D9CDBF">
		<div align="center">오픈타입</div></td>
		<td width="12" height="30" valign="middle">&nbsp;</td>
		<td width="423" height="30" valign="middle">
		<select name="OpenType" class="input">
		<option value="KR" selected="selected">한글</option>
		<option value="EN">영어</option>
		</select></td>
	</tr>
		<tr>
		<td height="30" bgcolor="#D9CDBF"><div align="center">용역제공기간</div></td>
			<td height="30" valign="middle">&nbsp;</td>
			<td height="30" valign="middle">
			<input name="OfferPeriod" class="input" size="20" value="2013081020120810">
			</td>
		</tr>  
        <tr>
          <td height="30" bgcolor="#D9CDBF"><div align="center">SkinColor</div></td>
          <td height="30" valign="middle">&nbsp;</td>
          <td height="30" valign="middle">
            <select name="SkinColor" class="input">
                <option value="" selected="selected">기본</option>
                <option value="VIOLET">바이올릿</option>
                <option value="BLUE">블루</option>
                <option value="GREEN">그린</option>
                <option value="RED">레드</option>
                <option value="YELLOW">옐로우</option>
            </select></td>
          </td>
        </tr> 
        <tr>
          <td width="151" height="30" bgcolor="#D9CDBF">
          <div align="center">Adaptor 모듈 사용여부</div></td>
          <td width="12" height="30" valign="middle">&nbsp;</td>
          <td width="423" height="30" valign="middle">
            <select name="SocketYN" class="input">
              <option value="N" selected>WEBLINK</option>
              <option value="Y">ADAPTOR</option>
            </select>
          </td>
        </tr>
        <tr>
          <td height="30" bgcolor="#D9CDBF"><div align="center">EncodeParameters</div></td>
          <td height="30" valign="middle">&nbsp;</td>
          <td height="30" valign="middle">
            <input name="EncodeParameters" class="input" size="30" value="">
          </td>
        </tr> 
    </table></td>
    <td rowspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="./images/btn_cost.jpg" width="81" height="23" onClick="return goInterface();"/></div></td>
  </tr>
</table>
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="28"><img src="./images/no.gif" width="20" height="50" /></td>
    <td width="602" valign="bottom"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="./images/bottom01.jpg">
      <tr>
        <td><img src="./images/no.gif" width="5" height="5" /></td>
      </tr>
    </table></td>
    <td width="20"><img src="./images/no.gif" width="20" height="50" /></td>
  </tr>
</table>
<input type="hidden" name="GoodsURL" size="50" value=""/>
</form>

<form name="payForm" method="post" accept-charset="EUC-KR">
<input type="hidden" name="payType">
<input type="hidden" name="GoodsCnt">
<input type="hidden" name="GoodsName">
<input type="hidden" name="Amt">
<input type="hidden" name="GoodsURL">
<input type="hidden" name="Moid">
<input type="hidden" name="MID">
<input type="hidden" name="ReturnURL">
<input type="hidden" name="ResultYN">
<input type="hidden" name="RetryURL">
<input type="hidden" name="mallUserID">
<input type="hidden" name="BuyerName">
<input type="hidden" name="BuyerAuthNum">
<input type="hidden" name="BuyerTel">
<input type="hidden" name="BuyerEmail">
<input type="hidden" name="ParentEmail">
<input type="hidden" name="BuyerAddr">
<input type="hidden" name="BuyerPostNo">
<input type="hidden" name="UserIP"          value="<?php echo $ip ?>">
<input type="hidden" name="MallIP">
<input type="hidden" name="VbankExpDate"   value="<?php echo $VbankExpDate ?>" >
<input type="hidden" name="BrowserType">
<input type="hidden" name="PayMethod">
<input type="hidden" name="ediDate"			value="<?php echo $ediDate ?>">
<input type="hidden" name="EncryptData">
<input type="hidden" name="MallReserved">
<input type="hidden" name="FORWARD" value="Y">
<input type="hidden" name="MallResultFWD"   value="N">
<input type="hidden" name="SUB_ID">
<input type="hidden" name="EncodingType">
<input type="hidden" name="OpenType" value="">
<input type="hidden" name="GoodsCl">
<input type="hidden" name="OfferPeriod" value="2013081020120810">
<input type="hidden" name="SkinColor">
<input type="hidden" name="SocketYN">
<input type="hidden" name="EncodeParameters">
</form>
<iframe src="./blank.html" name="payFrame" frameborder="no" width="0" height="0" scrolling="yes"  align="center"></iframe>
</body>
</html>
