<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$flag			= $_REQUEST['flag'];
$user_id		= $_REQUEST['user_id'];
$board_idx		= $_REQUEST['board_idx'];

$updateQuery = "update tbl_board_membership set etc_1='".$flag."', etc_4='".date('Y-m-d')."' where idx='".$board_idx."' ";
getFreeQueryCud($updateQuery);

$memberNo = date("ymdHi")."S".date("s");

if($flag=="Y"){
	$updateQuery = "update tbl_member set a_class='5', etc_4='Y', etc_5='".date('Y-m-d')."' ,etc_3='".$memberNo."',etc_6='".date("Y-m-d",strtotime("+12 month", time()))."', etc_9='sms' where user_id='".$user_id."' ";
	getFreeQueryCud($updateQuery);

	$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $user_id));

	$sname	= $arrInfo["list"][0]["user_name"];
	$sphone	= $arrInfo["list"][0]["mobile"];
	$msg = $sname."님, 정회원 승급이 정상적으로 완료되었습니다.
로그인 후 마이페이지에서 확인 가능합니다.";
	munja_send("lms", $sname, $sphone, $msg);
}

SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>