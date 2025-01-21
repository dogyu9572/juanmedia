<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$addrInfo = defaultAddress($_POST["idx"]);		## 기본배송지 설정

//DB해제
SetDisConn($dblink);
?>
