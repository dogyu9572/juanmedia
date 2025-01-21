<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


$yn			= $_REQUEST['yn'];
$sf			= $_REQUEST['sf'];
$gidx		= $_REQUEST['gidx'];

$updateQuery = "update tbl_shop_good set ".$sf."='".$yn."' where idx='".$gidx."' ";
//	echo $updateQuery;
getFreeQueryCud($updateQuery);
	


SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true