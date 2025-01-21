<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$Query = "SELECT * FROM tbl_member_address WHERE idx='".$_POST["idx"]."'";
$arrAddInfo = getFreeQueryR($Query);

if($arrAddInfo['list']['total'] > 0 ){
	$subQuery = "update tbl_shop_order_info set 
		ship_name		= '".$arrAddInfo['list'][0]['name']."',
		ship_mobile		= '".$arrAddInfo['list'][0]['mobile']."',
		ship_zip		= '".$arrAddInfo['list'][0]['zip']."',
		ship_address	= '".$arrAddInfo['list'][0]['address']."',
		ship_address_ext = '".$arrAddInfo['list'][0]['address_ext']."',
		order_comment	= '".$arrAddInfo['list'][0]['msg']."'
		WHERE order_no='".$_POST["orderno"]."'
	";
	getFreeQueryCud($subQuery);
	echo "OK";
}

//DB해제
SetDisConn($dblink);
?>