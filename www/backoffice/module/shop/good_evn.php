<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="insert"){
//	_DEBUG($_POST);
//	exit;
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertGood();

	if($RS){
		if($_POST['rt_url']){
			jsGo($_POST['rt_url'],"","");
		}else{
			jsGo("good.php","","");
		}
	}else{
		
		jsMsg("상품 등록에 실패 하였습니다.");
	//	jsHistory("-1") ;
		
	}

	//DB해제
	SetDisConn($dblink);

}else if($_GET['evnMode']=="copy"){
//	_DEBUG($_POST);
//	exit;
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_GET['idx']));

	$RS = copyGood($idx);

	if($RS){
		//	echo $RS;
		jsGo("good_info.php?idx=".$RS,"","선택한 상품이 복사되었습니다. 내용 확인 후 저장 버튼을 눌러주세요.");
	}else{
		jsMsg("상품 복사에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="edit"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['idx']));

	$RS = editGood($idx);

	if($RS==true){
		if($_POST['altYN']=="Y"){
			jsGo($_REQUEST['rt_url'],"","");
		}else{
			jsGo($_REQUEST['rt_url'],"","저장되었습니다");
		}
	}else{
		jsMsg("상품 수정에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="good_update"){	// 예약 금액 & 값 변경
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$Query = "UPDATE tbl_shop_order_info set 
		total_amount='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['total_amount'])."',
		pay_amount='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['total_amount'])."',
		re_amount='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['re_amount'])."',
		ipkum_date='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ipkum_date'])."',
		admin_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['admin_comment'])."',
		mail_sms='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mail_sms'])."'
		where order_no='".$_POST['order_no']."'
	";
	$RS = getFreeQueryCud($Query);

	if($RS==true){
		if($_POST['mail_sms']=="Y"){
			######################################################### 주문정보 ######################################################### ST
			$subQuery = " AND order_no ='".$_POST['order_no']."' ";
			$arrOrderInfo = getFreeView("tbl_shop_order_info", $subQuery, $col="*", $scale=0, $offset=0, $orderBy="");
			######################################################### 주문정보 ######################################################### ED
			################################## 알림톡 발송 ################################## ST
			kakaoAllim("003", $arrOrderInfo['list'][0]['order_name'], $arrOrderInfo['list'][0]['order_mobile'], $arrOrderInfo['list'][0]['order_id'], substr($arrOrderInfo["list"][0]["order_date"],0,10), $arrOrderInfo["list"][0]["order_no"], $arrOrderInfo['list'][0]['order_summary'], $arrOrderInfo['list'][0]['pay_amount'], "");
			################################## 알림톡 발송 ################################## ED
		}
		jsGo($_REQUEST['rt_url'],"","저장되었습니다");	
	}else{
		jsMsg("상품 수정에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['idx']));

	$RS = deleteGood($idx);

	if($RS==true){
		//jsGo("good.php","","");
		jsGo($_REQUEST['rt_url'],"","");
	}else{
		jsMsg("상품 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="insertOption"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertOption();

	if($RS==true){
		jsGo("option.php","","");
	}else{
		jsMsg("상품옵션 등록에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="editOption"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$code = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['opt_code']));

	$RS = editOption($code);

	if($RS==true){
		jsGo("option.php","","");
	}else{
		jsMsg("상품옵션 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="deleteOption"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$code = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['code']));

	$RS = deleteOption($code);

	if($RS==true){
		//jsGo("good.php","","");
		jsGo("option.php","","");
	}else{
		jsMsg("옵션 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="deleteOptionValue"){

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['idx']));

	$RS = deleteOptionValue($idx);

	if($RS==true){
		jsGo($_REQUEST['returnURL'],"","");
	}else{
		jsMsg("옵션항목 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="changeshow"){

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['idx']));
	$gb = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['gb']));

	$RS = editGoodShow($idx, $gb);

	if($RS==true){
		jsGo($_REQUEST['rt_url'],"","");
	}else{
		jsMsg("노출여부 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>
