<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
}else{
	$tp = "2";
}

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//장바구니 아이템 체크된것 삭제
if($_REQUEST['evnMode']=="deleteCartChecked"){
	$blnRS = deleteCartChecked($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
	if($blnRS==true){
		jsGo("/member/cart.php","","");
	}else{
		jsMsg("선택상품 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}
}

//장바구니 아이템 품절 삭제
if($_REQUEST['evnMode']=="deleteCartOut"){
	$blnRS = deleteCartOut($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
	if($blnRS==true){
		jsGo("/member/cart.php","","");
	}else{
		jsMsg("품절 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}
}

//장바구니 아이템 체크된것 주문
if($_REQUEST['evnMode']=="orderCartChecked"){
	$blnRS = preOrder($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
	if($blnRS==true){
		jsGo("/member/payment.php","","");
	}else{
		jsMsg("선택상품 주문에 실패 하였습니다.");
		jsHistory("-1") ;
	}
}

//장바구니 아이템 체크된것 주문
if($_REQUEST['evnMode']=="orderCartCheckedWish"){
	$blnRS = preOrderWish($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
	if($blnRS==true){
		jsGo("/member/payment.php","","");
	}else{
		jsMsg("선택상품 주문에 실패 하였습니다.");
		jsHistory("-1") ;
	}
}

if($_REQUEST['evnMode']=="orderCartCheckedMobile"){
	$blnRS = preOrder($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
	if($blnRS==true){
		jsGo("/shop.php?goPage=Order","","");
	}else{
		jsMsg("선택상품 주문에 실패 하였습니다.");
		jsHistory("-1") ;
	}
}

//DB해제
SetDisConn($dblink);
?>