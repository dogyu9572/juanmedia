<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$nick_name = mysqli_real_escape_string($dblink,$_REQUEST['nick_name']);

$arrList = getUserInfoNnickname($nick_name);

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	echo "1";
}else{
	echo "0";
}
?>