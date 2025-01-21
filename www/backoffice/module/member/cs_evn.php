<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="write"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertCs();
	
	//DB해제
	SetDisConn($dblink);

	if($RS){
		jsGo($_POST["returnURL"],"","등록 되었습니다.");
	}else{
		jsMsg("등록에 실패 하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="modify"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = updateCs(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['idx']));
	
	//DB해제
	SetDisConn($dblink);

	if($RS){
		jsGo($_POST["returnURL"],"","");
	}else{
		jsMsg("정보 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = deleteCs(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['idx']));		// 삭제처리
	if($RS2){
		jsGo($_POST['returnURL'],"","삭제되었습니다.");
	}else{
		jsGo($_POST['returnURL'],"","삭제에 실패했습니다.");
	}

	//DB해제
	SetDisConn($dblink);

}
?>