<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("������ �����ϴ�.");
	jsHistory("-1");
endif;

//DB����
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getCommentList("", "", 0, 0);
//_DEBUG($arrList);

//DB����
SetDisConn($dblink);
//Header("Content-type: file/unknown");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=".iconv("utf-8","euc-kr",$_SITE['NAME'])."_comment_".date(m)."��".date(d)."��".date(h)."��".date(i)."��.csv" );
header( "Content-Description: PHP4 Generated Data" );
header("Pragma: no-cache");
header("Expires: 0");

	echo "��ȣ,";
	echo "ID,";
	echo "�ۼ���,";
	echo "����,";
	echo "IP�ּ�,";
	echo "�����\n";

for ($i=0;$i<$arrList['list']['total'];$i++) {

	echo $i+1 . ",";
	echo str_replace(",",".",$arrList["list"][$i][user_id]) . ",";
	echo iconv("utf-8","euc-kr",$arrList["list"][$i][user_name]) . ",";
	echo "\"".str_replace(",",".", str_replace("\n"," ",str_replace("\r"," ", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i][comment]))))). "\",";
	echo $arrList["list"][$i][ip] . ",";
	echo $arrList["list"][$i][wdate] . "\n";
}
?>
