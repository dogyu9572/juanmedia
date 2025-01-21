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

if($_POST['evnMode']=="state"){
	$editRS = stateChangeAdmin($_REQUEST["states"], $_REQUEST["g_idx"]);
	if($editRS==true){
		echo "OK";
	}
}else if($_POST['evnMode']=="delete"){
	
}else {	
	
}

//DB해제
SetDisConn($dblink);
?>