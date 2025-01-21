<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$boardid = mysqli_real_escape_string($dblink, $_POST["boardid"]);

$idx = mysqli_real_escape_string($dblink, $_POST["idx"]);

$file_idx = mysqli_real_escape_string($dblink, $_POST["file_idx"]);

$fileinfo = getArticleFileInfo($boardid, $idx, $file_idx);

$arrData = array();

if($fileinfo["total"] > 0){
	//디비에서 파일정보 삭제
	$rs = mysqli_query($dblink, "DELETE FROM ".$GLOBALS["_conf_tbl"]["board_files"]." WHERE boardid='".$boardid."' AND idx='".$fileinfo["list"][0]['idx']."' ");

	if($rs){
		//디스크에서 파일 삭제
		@unlink($GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/".$fileinfo["list"][0]['re_name']);
	}
}else{
	$rs = false;
}

if($rs){
	$arrData["success"] = true;
}else{
	$arrData["success"] = false;
}

echo json_encode($arrData);


//DB해제
SetDisConn($dblink);
?>