<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


	$cat_no			= $_REQUEST['cat_no'];
	$cat_is_show	= $_REQUEST['cat_is_show'];	
	$updateQuery = "update tbl_category set cat_is_show='".$cat_is_show."' where cat_no='".$cat_no."' ";
	//echo $updateQuery;
	getFreeQueryCud($updateQuery);


SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>