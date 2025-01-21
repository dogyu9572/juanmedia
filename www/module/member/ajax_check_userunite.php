<?
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

$user_type		= $_REQUEST['user_type'];
$user_uniteid	= $_REQUEST['user_uniteid'];

$user_id = $user_type."_".$_SESSION['social']['id'];
$user_pw = $_SESSION['social']['id']."@pwd";


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserUnite($user_type, $user_id, $user_pw, $user_uniteid);

//DB해제
SetDisConn($dblink);

echo $arrInfo;
?>