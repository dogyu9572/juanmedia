<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$g_idx			= $_REQUEST['g_idx'];

$sql = "DELETE from tbl_shop_order_good where idx='".$g_idx."'";
getFreeQueryCud($sql);

//echo $sql;
	
SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true