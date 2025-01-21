<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$smsRS = selectCategory($_REQUEST["boardid"], $_REQUEST["evnMode"], $_REQUEST["editval"], $_REQUEST["sidx"]);

if($smsRS==true){
	echo "true";
}else{
	echo "false".$_REQUEST["sidx"]."//".$_REQUEST["editval"];
}

//DB해제
SetDisConn($dblink);
?>