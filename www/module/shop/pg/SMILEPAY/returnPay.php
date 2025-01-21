<?php  
	$PayMethod			= $_GET['PayMethod']; 
	$MID				= $_REQUEST['MID'];
	$Amt				= $_REQUEST['Amt'];
	$BuyerName			= $_REQUEST['BuyerName'];
	$GoodsName			= $_REQUEST['GoodsName'];
	$OID				= $_REQUEST['OID'];
	$mallUserID			= $_REQUEST['mallUserID'];
	$AuthDate			= $_REQUEST['AuthDate'];
	$AuthCode			= $_REQUEST['AuthCode'];
	$ResultCode			= $_REQUEST['ResultCode'];
	$ResultMsg			= $_REQUEST['ResultMsg'];
	$VbankNum			= $_REQUEST['VbankNum'];
	$VbankName			= $_REQUEST['VbankName'];
	                                 
	$MallReserved	    = $_REQUEST['MallReserved'];
	$TID				= $_REQUEST['TID'];
	$AcquCardCode		= $_REQUEST['AcquCardCode'];	
	$AcquCardName		= $_REQUEST['AcquCardName'];	
	$CardUsePoint		= $_REQUEST['CardUsePoint'];	
	$SignValue			= $_REQUEST['SignValue'];	
	$merchantKey		= "0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow==";
	$VerifySignValue	= base64_encode(md5(substr($TID,0,10).$ResultCode.substr($TID,10,5).$merchantKey.substr($TID,15,15)));

		// 결제 승인 

	  if("3001" == $ResultCode){ //CARD
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }

	  if("4000" == $ResultCode){ //BANK
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("4100" == $ResultCode){ //VBANK 체번완료
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("4110" == $ResultCode){ //VBANK 입금완료
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("A000" == $ResultCode){ //cellphone
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("7001" == $ResultCode){ //현금영수증
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }

 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title>스마트로::인터넷결제</title>
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
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="window.focus(); window.resizeTo(650, 780)"   onbeforeunload="" oncontextmenu='return false' ondragstart='return false' onselectstart='return false'>
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
        <td width="314" valign="bottom"><div align="right"><img src="./images/title01_.gif" /></div></td>
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
    <td rowspan="5">&nbsp;</td>
    <td height="50"><div align="center"><span class="style1">[ 결제 완료 결과입니다 ]</span></div></td>
    <td rowspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" background="./images/tb_patt03.jpg" bgcolor="#E2E2E2">
      <tr>
        <td width="151" height="30" bgcolor="#D9CDBF"><div align="center">지불수단</div></td>
        <td width="12" height="30" valign="middle">&nbsp;</td>
        <td width="423" height="30" valign="middle"><strong><?php echo $PayMethod ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상점ID</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $MID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">금액</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $Amt ?>원</strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자명</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $BuyerName ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상품명</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $GoodsName ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">주문번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $OID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자 ID</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $mallUserID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">승인번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><?php echo $AuthCode ?></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결과코드</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $ResultCode ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결과메시지</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $ResultMsg ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">거래고유번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $TID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">가상계좌번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $VbankNum ?></strong></td>
      </tr>
	   <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">가상계좌은행</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $VbankName ?></strong></td>
      </tr>
	   <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">응답 사인값</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $SignValue ?></strong></td>
      </tr>
	   <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">검증 사인값</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $VerifySignValue ?></strong></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><a href="#"><img src="./images/btn_close.gif" width="70" height="23" onClick="return window.close();"/></a></div></td>
  </tr>
</table>
<table width="650" border="0" cellspacing="0" cellpadding="0">
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
</body>
</html>
