<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/popup/popup.lib.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="createPopup"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$makeRS = insertPopup();

	if($makeRS==true){
		jsGo("popup_list.php","","팝업를 생성 하였습니다.");
	}else{
		jsMsg("팝업 생성에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="editPopup"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$editRS = editPopup($_POST['idx']);
	
	if($editRS==true){
		jsGo("popup_list.php","","팝업 정보를 수정 하였습니다.");
	}else{
		jsMsg("팝업 수정에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="deletePopup"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);
	
	$editRS = deletePopup($_POST['idx']);
	
	if($editRS==true){
		jsGo("popup_list.php","","팝업를 삭제 하였습니다.");
	}else{
		jsMsg("팝업 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>