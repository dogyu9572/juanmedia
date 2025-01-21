<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/banner/subbanner.lib.php";
if(!in_array("banner_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="insert"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertSubBanner();

	if($RS==true){
		jsGo("sub_banner.php","","");
	}else{
		jsMsg("배너 등록에 실패 하였습니다.");
	//	jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="update"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = trim($_POST['idx']);

	$RS = updateSubBanner($idx);
	if($RS==true){
		jsGo("sub_banner.php","","");
	}else{
		jsMsg("배너 수정에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = trim($_REQUEST['idx']);

	$RS = deleteSubBanner($idx);

	if($RS==true){
		jsGo("sub_banner.php","","");
	}else{
		jsMsg("배너 삭제에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>