<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="950" valign="top">
		<hr>
		<table width="100%" height="1" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
			<td>ȸ��Ұ� | �������� ��ȣ��å | copyright ��</td>
		  </tr>
		</table>
		</td>
  </tr>
</table>
</body>
</html>
<?
//���� ����Ҷ����� �ּ�����
//include_once $_SERVER['DOCUMENT_ROOT'] . "/module/memo/new_memo_check.inc.php";
//�ٸ������� ��������쿡�� �α� ���
if(!eregi($_SERVER["SERVER_NAME"],$_SERVER["HTTP_REFERER"])){
	include $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";
	$dblink = SetConn($_conf_db["main_db"]);
	insertLog();
	SetDisConn($dblink);
}
?>