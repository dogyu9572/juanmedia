<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="createMemberLevel"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$makeRS = createMemberLevel(mysqli_real_escape_string($GLOBALS['dblink'], $level_no), mysqli_real_escape_string($GLOBALS['dblink'], $level_name));

	if($makeRS==true){
		jsGo("member_level.php","","");
	}else{
		jsMsg("회원등급 생성에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="editMemberLevel"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$editRS = editMemberLevel($_POST['idx']);
	
	if($editRS==true){
		jsGo("member_level.php","","");
	}else{
		jsMsg("회원등급 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="deleteMemberLevel"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);
	
	$editRS = deleteArticleByIdx($GLOBALS["_conf_tbl"]["member_level"], mysqli_real_escape_string($GLOBALS['dblink'], $_POST['idx']));
	
	if($editRS==true){
		jsGo("member_level.php","","");
	}else{
		jsMsg("회원등급 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>