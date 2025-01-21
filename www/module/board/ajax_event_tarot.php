<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
########################################################## 이벤트 참여자 검색 ST
$arrBoardList = getBoardListBase($_POST['boardid'], "", "s", $_POST['sid'], 0, 0);

if($arrBoardList["list"]["total"]>0){
	echo "수정";
	updateTarot($_POST['boardid'], $_POST['sid'], $_POST['tca'], $_POST['val']);
}else{
	echo "입력";
	insertTarot($_POST['boardid'], $_POST['sid'], $_POST['tca'], $_POST['val']);
}
//echo $_POST['boardid']."/".$_POST['sid'];
########################################################## 이벤트 참여자 검색 ED

//DB해제
SetDisConn($dblink);
?>