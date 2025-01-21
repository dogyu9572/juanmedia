<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");

$cert = substr(md5(mktime()),0,6);

$email = $_REQUEST[email_id]."@".$_REQUEST[email_domain];
$tmpSubject = "[���̽���] ���� �����Դϴ�.";
$contents = "
<table border='0' cellpadding='3' cellspacing='1' width='800'>
<tr height='30' align='center'>
	<td width='15%' bgcolor='#646464'><font color='#ffffff'>������ȣ</font></td>
	<td width='85%' align='left'>".$cert."</td>
</tr>
<tr height='30' align='center'>
	<td colspan='2'>�� ������ȣ�� ȸ�����Զ��� �Է����ּ���</td>
</tr>
</table>
";

$mail = new smtp("mail.acetel.co.kr");  //��ü�߼��� ���, ������ �����Ҽ��� ����. 
//$mail->debug();  //������Ҷ� 
$mail->send($email, $_SITE["EMAIL"], $tmpSubject, $contents, "y");

if($arrList["total"] > 0){
	echo "1|".$cert;
}else{
	echo "0";
}
?>