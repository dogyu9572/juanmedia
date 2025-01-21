<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$deleteRS = deleteBoardAdmin($_REQUEST["boardid"], $_REQUEST["g_idx"]);

if($deleteRS==true){
	echo "true";
}else{
	echo "false".$_REQUEST["g_idx"]."//".$_REQUEST["boardid"];
}

//DB해제
SetDisConn($dblink);
?>