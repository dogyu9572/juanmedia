<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$tn		= $_REQUEST['tblid'];
$yn		= $_REQUEST['rval'];
$sf		= $_REQUEST['rname'];
$gidx	= $_REQUEST['ridx'];
$editr	= $_REQUEST['editr'];
if(!$editr){
	$editr = "idx";
}

$updateQuery = "update ".$tn." set ".$sf."='".$yn."' where ".$editr." in (".$gidx.") ";
	echo $updateQuery;
getFreeQueryCud($updateQuery);

SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>