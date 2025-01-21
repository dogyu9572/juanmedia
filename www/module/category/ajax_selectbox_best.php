<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$updateQuery = "update tbl_category set cat_best='".$_POST['bestnum']."' where cat_no=".$_POST['cat_no'];
getFreeQueryCud($updateQuery);

//DB해제
SetDisConn($dblink);
?>true