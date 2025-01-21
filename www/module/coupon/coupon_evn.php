<?
session_start();
include $_SERVER[DOCUMENT_ROOT] . "/common/conf/config.inc.php";
include $_SERVER[DOCUMENT_ROOT] . "/module/category/category.lib.php";
include $_SERVER[DOCUMENT_ROOT] . "/module/shop/shop.lib.php";
include $_SERVER[DOCUMENT_ROOT] . "/module/coupon/coupon.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['evnMode']=="goodCoupon"){
	
	$blnRS = insertCouponGood($_REQUEST['idx']);
	if($blnRS==true){
		jsMsg("쿠폰이 발급되었습니다.");
		jsHistory("-1") ;
	}else{
		jsMsg("이미 쿠폰이 발급되었습니다.");
		jsHistory("-1") ;
	}
}

if($_REQUEST['evnMode']=="codeCoupon"){
	
	$blnRS = insertCodeCouponGood($_REQUEST['coupon_no']);
	if($blnRS==true){
		jsGo("/mypage/ticket/coupon.php","","쿠폰이 발급되었습니다.");
		jsHistory("-1") ;
	}else{
		jsMsg("등록된 쿠폰이거나 없는 코드번호 입니다.\\n\\n확인후 다시 등록하시기 바랍니다.");
		jsHistory("-1") ;
	}
}

//DB해제
SetDisConn($dblink);
?>