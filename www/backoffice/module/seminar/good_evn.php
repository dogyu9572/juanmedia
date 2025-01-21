<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

## 하이덴탈 참가신청비 무료인경우 0원 처리
if($_POST['default_sale']=="Y"){
	$_POST['price'] = "0";
}

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
			jsGo("seminar_list.php","","");
		}
	}else{
		
		jsMsg("상품 등록에 실패 하였습니다.");
	//	jsHistory("-1") ;
		
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
			jsGo($_REQUEST['rt_url'],"","저장되었습니다.");
		}
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
