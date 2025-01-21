<?
session_start();
include ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/shop/review/review.lib.php");

if($_POST['evnMode']=="write"){
	//DB����
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = getMyOrderGood(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_no']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx']), $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

	if($RS == false){
		jsMsg("������ ��ǰ�� ���ؼ��� ���並 �ۼ� �Ͻ� �� �ֽ��ϴ�.");
		jsHistory("-2") ;
	}

	if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
		$RS = insertReview(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx']), $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]);
	}else{
		$RS = false;
	}
	//DB����
	SetDisConn($dblink);

	if($RS==true){
		jsGo("/shop.php?goPage=MyReview","","");
	}else{
		jsMsg("�� ��Ͽ� ���� �Ͽ����ϴ�.");
		jsHistory("-2") ;
	}
}else if($_POST['evnMode']=="deleteAjax"){
	//DB����
	$dblink = SetConn($_conf_db["main_db"]);

	if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
		$RS = deleteReview($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));
	}else{
		$RS = false;
	}
	//DB����
	SetDisConn($dblink);

	if($RS==true){
		echo "true";
	}else{
		echo "false";
	}
}
?>