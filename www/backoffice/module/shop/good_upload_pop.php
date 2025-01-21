<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>코드번호 올리기</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
* {
	font-size: 12px;
}
</style>
<script type="text/javascript">
function go_upload() {
	var f = document.getElementById("upload").value;
	if (f.length < 5)
	{
		alert("업로드할 파일을 선택해주세요");
		return;
	}
	var ext = f.substring(f.length-2.3);
	
	if (ext.toUpperCase() != "TXT" && ext.toUpperCase() != "CSV")
	{
		alert("txt나 csv형식의 파일만 올려주세요");
		return;
	}
	form.submit();
}
</script>
</head>

<body>
<form name="form" method="post" action="good_upload_ok.php" enctype="multipart/form-data">
<table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#FF9966">
  <tr> 
    <td> <strong>코드 등록</strong></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
	<td>&nbsp;</td>
  </tr>
  <tr>
	<td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="10" cellspacing="1" bgcolor="#999999">
  <tr bgcolor="#FFFFFF"> 
    <td align="center" bgcolor="cbe5ed"><div align="left">
        <input type="file" name="upload" id="upload" size="40">
      </div></td>
  </tr>
</table>
<p align="center"> <strong> 
  <input type="button" name="upload" value=" 올리기 " onclick="javascript:go_upload()">
  &nbsp; 
  <input type="button" value=" 닫기 " onclick="javascript:window.close()">
  <font color="#006600"><br>
  </font></strong><br>
</p>
</form>
</body>
</html>
