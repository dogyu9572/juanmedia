<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/product/product.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="insert"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertProduct();

	if($RS==true){
		jsGo("product.php","","");
	}else{
		jsMsg("제품 등록에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="edit"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['idx']));

	$RS = editProduct($idx);
	if($RS==true){
		jsGo($_REQUEST[rt_url],"","");
//		jsGo("product.php?cat_no=".$_POST['cat_no'],"","");
	}else{
		jsMsg("제품 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);


}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST[idx]));

	$RS = deleteProduct($idx);

	if($RS==true){
		jsGo("product.php","","");
	}else{
		jsMsg("제품 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}
?>