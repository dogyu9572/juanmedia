<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$orderno = $_REQUEST['order_no'];
$Query = "SELECT order_state from tbl_shop_order_info WHERE order_no='".$orderno."' ";
$arrOrderInfo = getFreeQueryR($Query);

echo $arrOrderInfo['list'][0]['order_state'];

//DB해제
SetDisConn($dblink);
?>