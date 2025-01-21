<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$subQuery = " AND biz='".$_REQUEST["bizsval"]."' ";
$arrBoardList = getBoardListBase("apply", "", "", "", 0, 0, $subQuery);	// 입금정보

echo "<option value=\"\">선택하세요.</option>";
for($i=0; $i < $arrBoardList["list"]["total"]; $i++){		
	echo "<option value='".$arrBoardList["list"][$i]['registration']."'>".$arrBoardList["list"][$i]['ko_company']."</option>";
}

//DB해제
SetDisConn($dblink);
?>