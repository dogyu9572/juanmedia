<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/giftcard/giftcard.lib.php";


if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="update"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$order_no = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['order_no']));

	$RS = setOrderInfoSeminar($order_no);
	if($RS==true){
		//주문자에게 메일발송
		jsMsg("저장되었습니다.");
		jsGo($_REQUEST['rt_url'],"","");
	}else{
		//jsMsg("주문정보 수정에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="giftcard"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['idx']));
	$price = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['price']));
	
	$RS = setGiftCard($idx, $price);

	if($RS==true){
		jsGo($_REQUEST['returnURL'],"","");
	}else{
		jsMsg("상품권 발행에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$order_no = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['order_no']));

	$RS = delOrderInfoAdmin($order_no);

	if($RS==true){
		//jsGo("order.php?"."&mode=".$_REQUEST['mode'],"","");
		jsMsg("주문정보를 삭제하였습니다.");
		jsHistory("-1") ;
	}else{
		jsMsg("주문정보 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>