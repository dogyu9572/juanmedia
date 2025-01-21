<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_POST['evnMode']=="return"){
	
	$RS = setOrderReturn($_POST["order_no"]);

	if($RS==true){
		echo "<script>
		alert('반품/교환 신청이 정상적으로 접수되었습니다.');
		opener.document.location.href='/shop.php?goPage=OrderList';
		self.close();
		</script>";	
	}else{
		jsMsg("반품/교환신청에 에러가 발생했습니다.");
		jsHistory("-1") ;
	}

} else if($_POST['evnMode']=="good_coupon"){	// 결제전 쿠폰 등록
	
	$RS = setGoodCoupon($_POST["couponIdx"], $_POST["couponPay"], $_POST["userID"], $_POST["g_idx"]);

	if($RS==true){
		echo "OK";
	}else{
		echo "ERROR";
	}
} else if($_POST['evnMode']=="good_coupon_zero"){	// 결제전 쿠폰 삭제
	
	$RS = setGoodCouponZero($_POST["userID"], $_POST["g_idx"]);

	if($RS==true){
		echo "OK";
	}else{
		echo "ERROR";
	}
} else if($_POST['evnMode']=="reserve_coupon"){		// 예약 상품 쿠폰 등록
	
	$RS = setReserveCoupon($_POST["couponIdx"], $_POST["couponPay"], $_POST["userID"], $_POST["orderno"]);

	if($RS==true){
		echo "OK";
	}else{
		echo "ERROR";
	}
} else if($_POST['evnMode']=="reserve_coupon_zero"){	// 예약 상품 쿠폰 삭제
	
	$RS = setReserveCouponZero($_POST["userID"], $_POST["orderno"]);

	if($RS==true){
		echo "OK";
	}else{
		echo "ERROR";
	}
} else if($_POST['evnMode']=="cancel"){
	
	$RS = setOrderCancel($_POST["order_no"]);

	if($RS==true){
		jsGo("/shop.php?goPage=MyPage","","취소 신청이 정상적으로 접수되었습니다.");
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
		//$arrMailInfo = getMailConfig(3);
		//sendMailShopInfo($arrInfo, $arrMailInfo);
		//주문자에게 메일발송
		echo "OK";
	}else{
		echo "ERROR";
	}

	//DB해제
	SetDisConn($dblink);
}else if($_POST['evnMode']=="reserve_update"){
	//	hiddenPayAmount
	$blnRS = setOrderReserve($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $_POST["order_no"]);

	if($blnRS==true){
		echo "OK";
	}else{
		echo "FAIL";
	}
}else if($_POST['evnMode']=="order_finish"){
	
	$blnRS = setOrderFinish($_POST["order_no"]);

	if($blnRS==true){
		echo "OK";
	}else{
		echo "FAIL";
	}
}else {	
	//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
	if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
		$tp = "1";
	}else{
		$tp = "2";
	}	
	$arrList = getPreOrderList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);

	//재고체크
	//	checkPreOderStock($arrList);


	//_POST 로 받는 주문번호가 기존에 주문된 주문번호인지 확인
	if(checkVaildOrderNo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST["order_no"]))==true){
		echo "NoCart";
		exit();
	}

	//_POST 정보를 주문정보 테이블에 입력
	$blnRS = setOrderInfo($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, $arrList["list"][0]["order_no"], $_REQUEST['order_state']);

	if($blnRS==true){
		if($arrList["list"][0]['stock_type']=="2"){	## 일반재고인 경우 카운트 마이너스
			$sql = "UPDATE tbl_shop_good SET stock = stock - 1 WHERE idx = '".$arrList["list"][0]['idx']."'	";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}
		echo "OK";
	}else{
		echo "FAIL | ".$_SESSION[$_SITE["DOMAIN"]]["SESSIONID"]." | ".$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]." | ".$tp." | ".$arrList["list"][0]["order_no"]." | ".$_REQUEST['order_state'];
	}
}

//DB해제
SetDisConn($dblink);
?>