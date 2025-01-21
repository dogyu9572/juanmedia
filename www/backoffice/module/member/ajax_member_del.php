<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//echo $_REQUEST["g_idx"];
//exit;
$deleteRS = deleteMemberAdmin($_REQUEST["g_idx"]);		//	탈퇴회원처리
//$deleteRS = outMemberAdmin($_REQUEST["g_idx"]);				// 삭제처리


if($deleteRS==true){
	echo "true";
}else{
	echo "false";
}

//DB해제
SetDisConn($dblink);
?>