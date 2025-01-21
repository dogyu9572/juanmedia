<?
session_start();
include ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/one_to_one/one_to_one.lib.php");

if($_POST['evnMode']=="write"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
		$RS = insertOneToOne();
	}else{
		$RS = false;
	}
	//DB해제
	SetDisConn($dblink);

	if($RS==true){
		jsGo("/shop.php?goPage=MyQna","","정상적으로 접수 되었습니다.");
	}else{
		jsMsg("글 등록에 실패 하였습니다.");
		jsHistory("-2") ;
	}
}else if($_POST['evnMode']=="deleteAjax"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
		$RS = deleteOneToOneUser($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx]));
	}else{
		$RS = false;
	}
	//DB해제
	SetDisConn($dblink);

	if($RS==true){
		echo "true";
	}else{
		echo "false";
	}
}
?>