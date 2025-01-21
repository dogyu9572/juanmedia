<?
session_start();
header("Content-Type: text/html; charset=utf-8");

include ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

$userid = $_REQUEST['userid'];
$uname = $_REQUEST['uname'];

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


$arrList = getUserPassSearch($userid, $uname);

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	echo "OK";
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["PWSEARCHID"] = $arrList["list"][0]['user_id'];
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["PWSEARCHNM"] = $arrList["list"][0]['user_name'];
}else{
	echo "NO";
}
?>