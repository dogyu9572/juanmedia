<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


	$kornm = $_REQUEST['kornm'];
	$engnm = $_REQUEST['engnm'];
	$cat_no = $_REQUEST['cat_no'];	

	$updateQuery = "update tbl_category set cat_name='".$kornm."', cat_engname='".$engnm."'  where cat_no='".$cat_no."' ";
//	echo $updateQuery;
	getFreeQueryCud($updateQuery);


SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true