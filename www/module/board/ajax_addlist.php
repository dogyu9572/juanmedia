<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$subQuery = " AND A.idx in (".$_REQUEST['idx'].") ";

$arrBoardList = getBoardListBaseNFile($_REQUEST['bid'], "", "", "", 0, 0, $subQuery);

//echo $subQuery."//".$_REQUEST['bid']."///".$arrBoardList["list"]["total"];

for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
	echo "<span style='padding:10px;'>".$arrBoardList["list"][$i]['subject'];
	if($arrBoardList["list"][$i]['ori_name']){
		echo "(".$arrBoardList["list"][$i]['ori_name'].")";
	}
	echo " <a href=\"javascript:void(0);\" onclick=\"fnAddDel('".$arrBoardList["list"][$i]['idx']."')\" style='font-weight: bold; color:red;'>X</a></span>";
}

//DB해제
SetDisConn($dblink);
?>