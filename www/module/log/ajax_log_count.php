<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$editRS = updateLogCount($_REQUEST["category"]);

if($editRS==true){
	echo "true";
}else{
	echo "false/".$_REQUEST["category"];
}

//DB해제
SetDisConn($dblink);
?>