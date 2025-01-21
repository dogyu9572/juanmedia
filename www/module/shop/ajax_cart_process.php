<?
session_start();
header("Content-Type: text/html; charset=euc-kr");
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

//장바구니에 아이템 담기
if($_REQUEST['evnMode']=="add"){	
	$arrQty = explode(",",$_REQUEST['qty']);
	$arrOptNM = explode(",",$_REQUEST['opt_nm']);
	for($i=0;$i<count($arrQty);$i++){
		//	$optName = urldecode(str_replace("_","%",$arrOptNM[$i]));
		$optName = urldecode(str_replace("_"," ",$arrOptNM[$i]));
		$blnRS = addCartHigh($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx']), $arrQty[$i], $optName, $_SESSION["MENUFLAG"]);		
	}
//장바구니 아이템 수량 수정
}else if($_REQUEST['evnMode']=="update"){
	$blnRS = updateCart($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
//장바구니 아이템 수량 수정
}else if($_REQUEST['evnMode']=="update_wish"){
	$blnRS = updateCartWish($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);

//장바구니 채크박스 선택 설정
}else if($_REQUEST['evnMode']=="onoff"){
	$blnRS = onoffCart($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);

//장바구니 아이템 개별 삭제
}else if($_REQUEST['evnMode']=="delete"){
	$blnRS = deleteCart($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);

//바로구매
}else if($_REQUEST['evnMode']=="direct"){
	$arrQty = explode(",",$_REQUEST['qty']);
	$arrOptNM = explode(",",$_REQUEST['opt_nm']);
	for($i=0;$i<count($arrQty);$i++){
		$optName = urldecode(str_replace("_"," ",$arrOptNM[$i]));
		$blnRS = directOrderHigh($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx']), $arrQty[$i], $optName, $i,$_REQUEST['orderflag']);
	}
	//	이전 한건 $blnRS = directOrder($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);

//바로구매2
}else if($_REQUEST['evnMode']=="direct2"){
	$blnRS = directOrder2($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);

//장바구니에서 한개 클릭 주문
}else if($_REQUEST['evnMode']=="orderOne"){
	$blnRS = preOrderOne($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx']), $tp);
}

//DB해제
SetDisConn($dblink);

if($_REQUEST['evnMode']=="add"){
	echo $blnRS;
}else{
	if($blnRS==true){
		echo "true";
	}else{
		echo "false";
	}
}
?>