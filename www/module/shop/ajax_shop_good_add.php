<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$order_no		= $_REQUEST['order_no'];
$order_id		= $_REQUEST['order_id'];
$g_idx			= $_REQUEST['g_idx'];
$g_qty			= $_REQUEST['g_qty'];

$sql = "select * from tbl_shop_good where idx='".$g_idx."'";
$arrInfo = getFreeQueryR($sql);

$sql = "INSERT INTO tbl_shop_order_good SET
		order_no='".$order_no."',
		order_id='".$order_id."',
		g_idx='".$g_idx."',
		g_cat_no='".$arrInfo["list"][0]["cat_no"]."',
		g_code='".$arrInfo["list"][0]["g_code"]."',
		g_name='".$arrInfo["list"][0]["g_name"]."',
		g_vendor='admin',
		g_brand='".$arrInfo["list"][0]["brand"]."',
		g_model='".$arrInfo["list"][0]["model"]."',
		g_price='".$arrInfo["list"][0]["price"]."',
		g_qty='".$g_qty."',
		g_point='0',
		g_opt_1='',
		g_opt_1_price='0',
		g_opt_2='',
		g_opt_2_price='0',
		g_opt_3='',
		g_opt_3_price='0',
		g_opt_4='',
		g_opt_4_price='0',
		g_opt_5='',
		g_opt_5_price='0',
		g_opt_rel_1='',
		g_opt_rel_1_price='0',
		g_opt_rel_2='',
		g_opt_rel_2_price='0',
		g_coupon_idx = '0',
		g_coupon_pay = '0',
		order_status ='X'
	";
getFreeQueryCud($sql);

//echo $sql;
	
SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true