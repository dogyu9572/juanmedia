<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="insert"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = joinMemberAdmin();
	
	//DB해제
	SetDisConn($dblink);

	if($RS==true){
		jsGo("member.php","","회원가입되었습니다.");
	}else{
		jsMsg("등록에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="edit"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = editMemberAdmin(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['user_id']));

	if($_POST['talk']=="1" && $_POST['user_level']=="3" ){
	//	kakaoApiTalk("M01", $_POST['etc_1'], $_POST['mobile'], $_POST['user_id'], "", "", "", "", "");
	//	$Query = "update tbl_member set smsdt = now() where user_id='".$_POST['user_id']."'";
	//	$rs = mysqli_query($GLOBALS['dblink'], $Query);
	}
	
	//DB해제
	SetDisConn($dblink);

	if($RS==true){
		jsGo($_REQUEST['rt_url'],"",  "수정되었습니다.");
	}else{
		jsMsg("정보 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = deleteMember(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['user_id']));		// 탙퇴회원처리
	//$RS2 = outMember(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['user_id']));		// 삭제처리
	if($RS2 == true){
		if($_POST['returnURL']){
			jsGo($_POST['returnURL'],"",$_REQUEST['user_id'] . "님 정상적으로 탈퇴처리되었습니다.");
		}else{
			jsGo("member.php","",$_REQUEST['user_id'] . "님 정상적으로 탈퇴처리되었습니다.");
		}
	}else{
		jsGo("member.php","","삭제중 오류가 발생하였습니다.");
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="out"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = outMember(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['user_id']));
	if($RS2 == true){
		if($_POST['returnURL']){
			jsGo($_POST['returnURL'],"",$_REQUEST['user_id'] . "님 정상적으로 삭제 되었습니다.");
		}else{
			jsGo("member_out.php","",$_REQUEST['user_id'] . "님 정상적으로 삭제 되었습니다.");
		}
	}else{
		jsGo("member_out.php","","삭제중 오류가 발생하였습니다.");
	}


	//DB해제
	SetDisConn($dblink);

}
?>