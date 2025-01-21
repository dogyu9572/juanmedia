<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/review/review.lib.php";
if(!in_array("review_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="edit"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']);

	$RS = updateReview("",$idx);

	if($RS==true){
		jsGo("review.php" ,"","");
	}else{
		jsMsg("정보수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = deleteReview("", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

	if($RS==true){
		jsGo($_REQUEST['rt_url'],"","");
	}else{
		jsMsg("정보삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>