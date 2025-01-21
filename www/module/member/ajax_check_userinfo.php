<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

$user_mobile	= $_REQUEST['user_mobile'];

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserFindMobile($user_mobile);

//DB해제
SetDisConn($dblink);

if($arrInfo["total"] > 0){
	echo $arrInfo['list'][0]['user_id'];
}else{
	echo "N";
}
?>