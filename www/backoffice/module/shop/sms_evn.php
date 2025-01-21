<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";


if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="sms"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = sendAdminSmsInfo($_POST['content'], $_POST['hnum']);

	if($RS==true){
		jsMsg($_POST['hnum']."문자발송");
		jsHistory("-1") ;
	}else{
		jsMsg("문자발송에 실패 하였습니다.");
		jsHistory("-1") ;
	}
	//DB해제
	SetDisConn($dblink);
}
?>

<?=$_POST['content']?>
<?=$_POST['hnum']?>