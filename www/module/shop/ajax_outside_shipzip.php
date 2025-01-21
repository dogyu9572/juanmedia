<?
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//	$updateRS = updateGoodOrderState(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_state"]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["idx"]));

################################################# 도서산간 ################################################# ST
$subQuery = " AND do_zip = '".$_REQUEST["zipcode"]."' ";
//	$subQuery = " AND do_zip = '22386' ";
$arrOutSide = getFreeView("tbl_zipcode_outside", $subQuery, "*", 0, 0, "");
$shipOutsideFlag = false;
if($arrOutSide['total']>0){	## 도서산간인 배송지
	$shipOutsideFlag = true;
}
################################################# 도서산간 ################################################# ED


//DB해제
SetDisConn($dblink);

if($shipOutsideFlag){
	echo "true";
}else{
	echo "false";
}
?>