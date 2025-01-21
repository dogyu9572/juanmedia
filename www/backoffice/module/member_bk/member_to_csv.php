<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("������ �����ϴ�.");
	jsHistory("-1");
endif;

//DB����
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getMemberList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 0, 0);
//_DEBUG($arrList);

//DB����
SetDisConn($dblink);

//Header("Content-type: file/unknown");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=".iconv("utf-8","euc-kr",$_SITE['NAME'])."_member_".date(m)."��".date(d)."��".date(h)."��".date(i)."��.csv" );
header( "Content-Description: PHP4 Generated Data" );
header("Pragma: no-cache");
header("Expires: 0");

	echo "��ȣ,";
	echo "ID,";
	echo "�̸�,";
	echo "����,";
	echo "�̸���,";
	echo "�����ȣ,";
	echo "�ּ�,";
	echo "���ּ�,";
	echo "��ȭ��ȣ,";
	echo "�޴���,";
	echo "���ϼ���,";
	echo "�α��μ�,";
	echo "�������α���,";
	echo "�����,";
	echo "������\n";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	echo $i+1 . ",";
	echo $arrList["list"][$i]['user_id'] . ",";
	echo iconv("utf-8","euc-kr",$arrList["list"][$i]['user_name']) . ",";
	echo $arrList["list"][$i]['birth'] . ",";
	echo str_replace(",",".", iconv("utf-8","euc-kr",$arrList["list"][$i]['email'])) . ",";
	echo str_replace(",",".", iconv("utf-8","euc-kr",$arrList["list"][$i]['zip'])) . ",";
	echo str_replace(",",".", iconv("utf-8","euc-kr",$arrList["list"][$i]['address'])) . ",";
	echo str_replace(",",".", iconv("utf-8","euc-kr",$arrList["list"][$i]['address_ext'])) . ",";
	echo str_replace(",",".", $arrList["list"][$i]['phone']) . ",";
	echo str_replace(",",".", $arrList["list"][$i]['mobile']) . ",";
	echo $arrList["list"][$i]['email_accept']=="Y"?"��,":"�ƴϿ�,";
	echo $arrList["list"][$i]['login_count'] . ",";
	echo $arrList["list"][$i]['login_last'] . ",";
	echo $arrList["list"][$i]['wdate'] . ",";
	echo $arrList["list"][$i]['udate'] . "\n";
}
?>