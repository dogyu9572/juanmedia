<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title>:::초기페이지:::</title>
<script language="javascript">
<!--
// 결제처리
function goPay() {
	var payURL = 'mainPay.php';
	location.href = payURL;
}

// 취소처리
function goCancelPay() {
	var cxlURL = './mainCancelPay.php';
	location.href = cxlURL;
}
-->
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="tranMgr" method="post" action="">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="middle">
		  <table width="678" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			  
          <td height="467" valign="top" background="images/bag_img.jpg">
		    <table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="124" height="309">&nbsp;</td>
                <td width="116">&nbsp;</td>
              </tr>
              <tr> 
                <td align="right"><img src="images/bt_01.gif" width="104" height="31" onClick="return goPay();" style="cursor:hand"></td>
                <td align="right"><img src="images/bt_02.gif" width="104" height="31" onClick="return goCancelPay();" style="cursor:hand"></td>
              </tr>
            </table></td>
			</tr>
		  </table>
	</td>
  </tr>
</table>
</form>
</body>
</html>