<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/research/research.lib.php";
if(!in_array("research_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="create"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertResearch();

	if($RS==true){
		jsGo("research.php","","설문을 등록 하였습니다.");
	}else{
		jsMsg("설문 등록에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="edit"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['idx']));

	$RS = editResearch($idx);
	if($RS==true){
		jsGo("research_info.php?idx=".$idx,"","");
	}else{
		jsMsg("설문 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="editAnswer"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$r_idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['r_idx']));
	$rq_idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['rq_idx']));

	$RS = editResearchAnswer($r_idx, $rq_idx);
	if($RS==true){
		jsGo("research_info.php?idx=".$r_idx,"","");
	}else{
		jsMsg("설문답변 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST[idx]));

	$RS = deleteResearch($idx);

	if($RS==true){
		jsGo("research.php","","설문을 삭제 하였습니다.");
	}else{
		jsMsg("설문 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>