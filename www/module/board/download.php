<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrFile = getArticleFileInfo(mysqli_real_escape_string($GLOBALS['dblink'],$_GET['boardid']), mysqli_real_escape_string($GLOBALS['dblink'],$_GET['b_idx']), mysqli_real_escape_string($GLOBALS['dblink'],$_GET['idx']));

if($arrFile["total"] > 0){
	$src_file = $_SITE["BOARD_DATA"] . "/" . $arrFile["list"][0]['boardid'] . "/" . $arrFile["list"][0]['re_name'];

	//다운로드 수 업데이트
	$sql  = "UPDATE " .$GLOBALS["_conf_tbl"]["board_files"]." SET ";
	$sql .= " download = download + 1 ";
	$sql .= "WHERE idx = '".$arrFile["list"][0]['idx']."' ";
	mysqli_query($GLOBALS['dblink'], $sql);

	// 모든 파일 강제 다운로드 처리
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.iconv("UTF-8","EUC-KR",$arrFile["list"][0]['ori_name']).'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($src_file));

	if(is_file($src_file)){
		$fp = fopen($src_file, "rb");
		fpassthru($fp);
		fclose($fp);
	}
	exit;
}else{
	echo "<script>alert('해당 파일이 없습니다.'); window.close();</script>";
}

//DB해제
SetDisConn($dblink);
?>
