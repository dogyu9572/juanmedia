<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/html_contents/html_contents.lib.php";


if($_POST['evnMode']=="createContents"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$code = mysqli_real_escape_string($GLOBALS['dblink'], str_replace(".","",str_replace("/","", trim($_REQUEST[id]))));
	$makeRS = createContents(mysqli_real_escape_string($GLOBALS['dblink'], $code));

	if($makeRS==true){
		jsGo("html_contents.php","","컨텐츠을 생성 하였습니다.");
	}else{
		jsMsg("컨텐츠 생성에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="editContents"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$editRS = editContents($_POST['idx']);
	
	if($editRS==true){
		jsGo("html_contents.php","","컨텐츠 정보를 수정 하였습니다.");
	}else{
		jsMsg("컨텐츠 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="deleteContents"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);
	
	$editRS = deleteContents($_POST['idx']);
	
	if($editRS==true){
		jsGo("html_contents.php","","컨텐츠을 삭제 하였습니다.");
	}else{
		jsMsg("컨텐츠 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>