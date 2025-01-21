<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrMidx = explode(",",$_REQUEST["memberidx"]);

for($i=0; $i < count($arrMidx); $i++){
	$editRS = setReserveOrder($arrMidx[$i], $_REQUEST["gidx"]);
}

if($editRS==true){
	echo "OK";
}

//DB해제
SetDisConn($dblink);
?>