<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$order_no	= $_POST['order_no'];

$Query = "UPDATE tbl_shop_order_info SET cdate = NOW() WHERE order_no='".$order_no."' ";

$arrBoardList = getFreeQueryCud($Query);

//	echo $Query;
		
//DB해제
SetDisConn($dblink);
?>