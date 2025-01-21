<?
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/recruit/recruit.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_POST['evnMode']=="intEseid" || $_POST['evnMode']=="intEseidT"){

	//기본정보 등록
	$writeRS = insertInfo01();

	if($writeRS==true){
		if($_POST['evnMode']=="intEseid"){
			jsGo("/about/db_02.php","","");
		} else {
			jsGo("/about/db_01.php","","저장되었습니다.");
		}
	}else{
		jsMsg("등록에 실패하였습니다.");
		jsHistory("-1") ;
	}

}

if($_POST['evnMode']=="intBeokm" || $_POST['evnMode']=="intBeokmT"){

	if(!$_SESSION["RECRUITIDX"]){
		jsGo("/about/db_01.php","","기본정보가 입력되지 않았습니다..");
	}
	//기본정보 등록
	$writeRS = insertInfo02();

	if($writeRS==true){
		if($_POST['evnMode']=="intBeokm"){
			jsGo("/about/db_03.php","","");
		} else {
			jsGo("/about/db_02.php","","저장되었습니다.");
		}
	}else{
		jsMsg("등록에 실패하였습니다.");
		jsHistory("-1") ;
	}

}

if($_POST['evnMode']=="intYdmopw" || $_POST['evnMode']=="intYdmopwT"){

	if(!$_SESSION["RECRUITIDX"]){
		jsGo("/about/db_01.php","","기본정보가 입력되지 않았습니다..");
	}
	//기본정보 등록
	$writeRS = insertInfo03();

	if($writeRS==true){
		if($_POST['evnMode']=="intYdmopw"){
			jsGo("/about/db_04.php","","");
		} else {
			jsGo("/about/db_03.php","","저장되었습니다.");
		}
	}else{
		jsMsg("등록에 실패하였습니다.");
		jsHistory("-1") ;
	}

}

if($_POST['evnMode']=="intFeisld" || $_POST['evnMode']=="intFeisldT"){

	if(!$_SESSION["RECRUITIDX"]){
		jsGo("/about/db_01.php","","기본정보가 입력되지 않았습니다..");
	}
	//기본정보 등록
	$writeRS = insertInfo04();

	if($writeRS==true){
		if($_POST['evnMode']=="intFeisld"){
			jsGo("/about/db_05.php","","");
		} else {
			jsGo("/about/db_04.php","","저장되었습니다.");
		}
	}else{
		jsMsg("등록에 실패하였습니다.");
		jsHistory("-1") ;
	}

}

if($_POST['evnMode']=="intOdkwd" || $_POST['evnMode']=="intOdkwdT"){

	if(!$_SESSION["RECRUITIDX"]){
		jsGo("/about/db_01.php","","기본정보가 입력되지 않았습니다..");
	}
	//기본정보 등록
	$writeRS = insertInfo05();

	if($writeRS==true){
		if($_POST['evnMode']=="intOdkwd"){
			unset($_SESSION['RECRUITIDX']);
			jsGo("/about/db_finish.php","","");
		} else {
			jsGo("/about/db_05.php","","저장되었습니다.");
		}
	}else{
		jsMsg("등록에 실패하였습니다.");
		jsHistory("-1") ;
	}

}

?>