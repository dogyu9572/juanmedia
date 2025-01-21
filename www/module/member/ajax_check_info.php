<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

$email = ($_REQUEST['info_value']);

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getUserCheckInfo($email);

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	echo "1";
}else{
	echo "0";
}
?>