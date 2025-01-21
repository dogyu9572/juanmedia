<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";

if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	echo "OK";
	exit;
}

?>