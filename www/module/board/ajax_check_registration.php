<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$user_id = $_REQUEST['user_id'];

//사용금지 아이디 체크
if(in_array(strtolower($user_id),$_SITE["MEMBER"]["DONT_USE_ID"])){
	echo "1";
	exit;
}

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$subQuery = " AND registration = '".$user_id."' AND category='".date("Y")."' ";
$arrList = getBoardListBase("apply", "", "", "", 0, 0, $subQuery);


$subQuery = " AND registration = '".$user_id."' AND category='".date("Y", strtotime("-1 years"))."' ";
$arrOldList = getBoardListBase("apply", "", "", "", 0, 0, $subQuery);

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	echo "1";
}else{
	if($arrOldList["total"] > 0){
		echo "re";
		$_SESSION["TEMP"]["REG"] = $user_id;
	}else{
		echo "0";
	}
}
?>