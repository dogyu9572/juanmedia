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
	$arrGVal = explode(",",$_REQUEST['g_val']);
	for($i=0;$i<count($arrGVal);$i++){
		$arrGood = explode("|",$arrGVal[$i]);
		$good_idx = $arrGood[0];
		$good_qty = $arrGood[1];

		$blnRS = addCartHigh($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, $good_idx, $good_qty, "", "");		
		//echo $good_idx."/".$good_qty."|";
	}
}else if($_REQUEST['evnMode']=="add_order"){	
	$arrList = getReserveOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$_REQUEST['order_no']);	
	for($i=0;$i<$arrList["total"];$i++){
		if($arrList['list'][$i]['model']!="S"){
			$blnRS = addCartHigh($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, $arrList['list'][$i]['idx'], $arrList["list"][$i]["g_qty"], "", "");	
		}
	}
}else if($_REQUEST['evnMode']=="add_wish"){	
	$arrGVal = explode(",",$_REQUEST['g_val']);
	for($i=0;$i<count($arrGVal);$i++){
		$arrGood = explode("|",$arrGVal[$i]);
		$good_idx = $arrGood[0];
		$good_qty = $arrGood[1];

		$blnRS = addCartWish($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, $good_idx, $good_qty, "", "");		
		//echo $good_idx."/".$good_qty."|";
	}
}else if($_REQUEST['evnMode']=="del_wish"){	
	$blnRS = deleteCartWish($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp);
}

//DB해제
SetDisConn($dblink);

if($blnRS==true){
	echo "true";
}else{
	echo "false";
}
?>