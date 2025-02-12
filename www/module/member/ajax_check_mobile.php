<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/class.http.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/Services_JSON.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/class.EmmaSMS.php";

$sms_id = "bobsnu";
$sms_passwd = "qkqtmsn1!";


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getUserFindMobile(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[mobile]));

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	
	/*$str = "1234567890";
	$acode = substr(str_shuffle($str),0,4);

	$sms_to = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[mobile]);
	$sms_from = "031-216-6512";
	$sms_date = "";
	$sms_msg = "회원인증 번호 입니다. [".$acode."]";
	$sms_type = "L";    // 설정 하지 않는다면 80byte 넘는 메시지는 쪼개져서 sms로 발송, L 로 설정하면 80byte 넘으면 자동으로 lms 변환

	$sms = new EmmaSMS();
	$sms->login($sms_id, $sms_passwd);
	$ret = $sms->send($sms_to, $sms_from, $sms_msg, $sms_date, $sms_type);*/

	echo 1;
}else{
	echo "0";
}
?>