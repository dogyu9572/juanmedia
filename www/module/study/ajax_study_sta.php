<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$pidx = $_REQUEST["pidx"]??"0";

$RS = studyUpdate($_REQUEST["vodtype"], $_REQUEST["appidx"], $_REQUEST["durat"], $_REQUEST["curr"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $pidx);

if($RS){
	echo "true";
}else{
	echo "false".$_REQUEST["vodtype"]."//".$_REQUEST["appidx"];
}

//DB해제
SetDisConn($dblink);
?>