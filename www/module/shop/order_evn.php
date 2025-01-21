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

if($_POST['evnMode']=="return"){			//교환
	
	for ($i=0; $i<count($_POST['gidx']); $i++){
		$RS = setOrderReturn($_POST["order_no"], $_POST["gidx"][$i]);
	}

	if($RS==true){
		jsGo("/member/return_end.php?orderno=".$_POST["order_no"],"","교환 신청이 정상적으로 접수되었습니다.");
	}else{
		jsMsg("교환신청에 에러가 발생했습니다.");
		jsHistory("-1") ;
	}
} else if($_POST['evnMode']=="refund"){		//환불

	for ($i=0; $i<count($_POST['gidx']); $i++){
		$RS = setOrderRefund($_POST["order_no"], $_POST["gidx"][$i]);
	}

	if($RS==true){
		jsGo("/member/return_end.php?orderno=".$_POST["order_no"],"","환불 신청이 정상적으로 접수되었습니다.");
	}else{
		jsMsg("환불신청에 에러가 발생했습니다.");
		jsHistory("-1") ;
	}
} else if($_POST['evnMode']=="bind"){		// 묶음 배송
	$RS = setOrderBind($_POST["order_no"]);
	
	if($RS==true){
		jsGo("/member/combined_end.php?orderno=".$_POST["order_no"],"","");
	}else{
		jsMsg("에러가 발생했습니다.");
		jsHistory("-1") ;
	}
} else if($_POST['evnMode']=="addedit"){

	$RS = setOrderAddEdit($_POST["order_no"]);

	if($RS==true){
		jsGo("/shop.php?goPage=MyPage","","배송지 정보가 변경되었습니다.");
	}else{
		jsMsg("에러가 발생했습니다.");
		jsHistory("-1") ;
	}

} else if($_POST['evnMode']=="cancel"){

	//현재 주문정보 가져오기
	$arrInfo = getOrderInfoAdmin($_POST["order_no"]);
	//	kakaoAllim("19", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo["list"][0]["order_summary"], $arrInfo["list"][0]["order_no"], "", "", "");

	if($_POST["goodidx"]){
		$RS = setOrderCancel($_POST["order_no"], $_POST["goodidx"]);	## 부분 취소		
	}else{
		$RS = setOrderCancel($_POST["order_no"], "");	## 전체 취소
	}

	if($RS==true){
		jsGo("/member/mypage.php","","취소가 정상적으로 처리되었습니다.");
	}else{
		jsMsg("취소요청중 에러가 발생했습니다.");
		jsHistory("-1") ;
	}
} else if($_POST['evnMode']=="payCancel"){
	if($_POST["goodidx"]){
		$RS = setOrderCancel($_POST["order_no"], $_POST["goodidx"]);	## 부분 취소
		#################### pg사 취소처리 해야함 ####################
	}else{
		$RS = setOrderCancel($_POST["order_no"], "");	## 전체 취소
		#################### pg사 취소처리 해야함 ####################
	}

	if($RS==true){
		jsGo("/include/order_delivery_inquiry.php","","취소가 정상적으로 처리되었습니다.");
	}else{
		jsMsg("취소요청중 에러가 발생했습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$order_no = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['order_no']));
	$direct_gb = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['directpoint']);

	$arrInfo = getOrderInfoAdmin(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));
	$RS = delOrderInfoAdmin($order_no, $direct_gb);

	if($RS==true){
		//주문자에게 메일발송
		$arrMailInfo = getMailConfig(3);
		sendMailShopInfo($arrInfo, $arrMailInfo);
		//주문자에게 메일발송

		jsGo("/shop.php?goPage=OrderList","","");
	}else{
		jsMsg("주문정보 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else {

	//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
	if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
		$tp = "1";
	}else{
		$tp = "2";
	}
	$arrList = getPreOrderList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);

	//재고체크
	checkPreOderStock($arrList);


	//_POST 로 받는 주문번호가 기존에 주문된 주문번호인지 확인
	if(checkVaildOrderNo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST["order_no"]))==true){
		jsGo("/shop.php?goPage=Cart","parent","이미 주문이 완료되었습니다.");
	}

	//_POST 로 받은 주문번호가 구매직전 장바구니에 있는지 확인
	if($_POST["order_no"] != $arrList["list"][0]["order_no"]){
		jsGo("/shop.php?goPage=Cart","parent","잘못된 주문 정보 입니다. 주문 장바구니에 해당 주문건이 없습니다.");
	}

	//_POST 정보를 주문정보 테이블에 입력

	$blnRS = setOrderInfo($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, $arrList["list"][0]["order_no"], $_REQUEST['order_state']);

	if($blnRS==true){
		if($_REQUEST['order_state'] == "10") {
			jsGo("/shop.php?goPage=Payment&order_no=".$arrList["list"][0]["order_no"],"parent","");
		} else {
			//주문자에게 메일발송
			$arrInfo = getOrderInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));
			$arrMailInfo = getMailConfig(1);
			sendMailShopInfo($arrInfo, $arrMailInfo);
			//주문자에게 메일발송

			jsGo("/shop.php?goPage=Thanks&order_no=".$arrList["list"][0]["order_no"],"parent","");
		}
	}
}

//DB해제
SetDisConn($dblink);
?>