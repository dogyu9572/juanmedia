<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
$coupon_idx=$_POST['coupon_idx'];
$giftcard_idx=$_POST['giftcard_idx'];

if($coupon_idx!=""){
	usecoupon($coupon_idx);
}
if($giftcard_idx!=""){
	usegiftcard($giftcard_idx);
}

		
//DB해제
SetDisConn($dblink);
?>