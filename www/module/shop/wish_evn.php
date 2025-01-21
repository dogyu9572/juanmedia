<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//장바구니 아이템 체크된것 삭제
if($_REQUEST['evnMode']=="deleteWishChecked"){
	$blnRS = deleteWishChecked($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);
	if($blnRS==true){
		jsGo("/shop.php?goPage=WishList","","");
	}else{
		jsMsg("선택상품 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}
}

//DB해제
SetDisConn($dblink);
?>