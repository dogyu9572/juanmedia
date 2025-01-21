<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";

	$PayMethod			= $_REQUEST['PayMethod'];
	$PayName            = $_REQUEST['PayName'];
	$vMID				= $_REQUEST['MID'];
	$TID				= $_REQUEST['TID'];
	$BuyerName			= $_REQUEST['BuyerName'];
	$CancelAmt			= $_REQUEST['CancelAmt'];
	$CancelDate			= $_REQUEST['CancelDate'];
	$CancelTime			= $_REQUEST['CancelTime'];
	$CancelNum			= $_REQUEST['CancelNum'];
	$ResultCode			= $_REQUEST['ResultCode'];
	$ResultMsg			= $_REQUEST['ResultMsg'];
	

	 // ���� ���
	  if("2001" == $ResultCode){
	     // ��� ������ DBó�� �ϼ���.
	     //TID ���� ����� ������ ����� UPDATE, �������� ���� ��� INSERT

	  }
	  if("2211" == $ResultCode){
	     // ȯ��
	  }
	
	

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title>����Ʈ��::���ͳݰ���</title>
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

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="window.focus(); window.resizeTo(520, 550)"   onbeforeunload="" oncontextmenu='return false' ondragstart='return false' onselectstart='return false'>
<table width="500" border="0" cellpadding="0" cellspacing="0" bgcolor="e5e5e5">
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
    <td valign="bottom"><table width="298" height="73" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="298" valign="bottom"><div align="right"><img src="./images/title03_.gif" /></div></td>
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
    <td><div align="center"><span class="style3">[ ��Ұ� �Ϸ� �Ǿ����ϴ� ]</span></div></td>
    <td rowspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td height="15">&nbsp;</td>
  </tr>
  <tr>
    <td height="15"><table width="100%" border="0" cellpadding="0" cellspacing="1" background="./images/tb_patt03.jpg" bgcolor="#E2E2E2">
      <tr>
        <td width="103" height="30" bgcolor="#D9CDBF"><div align="center">��û�޽���</div></td>
        <td width="12" height="30" valign="middle">&nbsp;</td>
        <td width="350" height="30" valign="middle"><strong><?=$CancelMSG?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">��Ұ��</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?=$ResultMsg."[".$ResultCode."]"?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">��������</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?=$PayName?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">��ұݾ�</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?=$CancelAmt?>��</strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">���� ID<span class="style3"></span></div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?=$MID?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">�ŷ���ȣ</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?=$TID?></strong></td>
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
          <td><div align="center"><img src="./images/btn_close.gif" width="70" height="23" onClick="return window.close();"/>            </div>
            <div align="right"></div></td>
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
</body>
</html>
