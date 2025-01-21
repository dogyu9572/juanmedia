<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


$yn			= $_REQUEST['yn'];
$order_no	= $_REQUEST['order_no'];

$updateQuery = "update tbl_shop_order_info set certificateYN='".$yn."' where order_no='".$order_no."' ";
//	echo $updateQuery;
getFreeQueryCud($updateQuery);
	


SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true