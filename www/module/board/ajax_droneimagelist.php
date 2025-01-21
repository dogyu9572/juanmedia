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

$orderbyName = "image1by";
if($_REQUEST['fname']=="image2idxs"){
	$orderbyName = "image2by";
}

$orderby = $_REQUEST['orderby'];
if($orderby){
	$arrOrder = explode(",",$orderby);
}

for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
	$orderNum = "1";
	if($arrOrder[$i]){
		$orderNum = $arrOrder[$i];
	}
	$imgsrc[$i] = "/uploaded/board/".$_REQUEST['bid']."/".$arrBoardList["list"][$i]['re_name'];
	echo "<input type=\"hidden\" name=\"".$_REQUEST['fname']."[]\" value=\"".$arrBoardList["list"][$i]['idx']."\">";
	echo "<tr><td><input type=\"text\" class=\"w1 numberOnly\" name=\"".$orderbyName."[]\" maxlength=\"2\" value=\"".$orderNum."\" style=\"text-align:center;\"></td>";
	echo "<td><img src=\"".$imgsrc[$i]."\" style=\"max-height:50px;max-width:100px;\"></td>";
	echo "<td>".$arrBoardList["list"][$i]['subject']."</td>";
	echo "<td><a href=\"javascript:void(0);\" onclick=\"fnAddDel('".$arrBoardList["list"][$i]['idx']."','".$_REQUEST['fname']."')\" class=\"btn del\" style=\"display: inline-block;\">삭제</a></td></tr>";
}

//DB해제
SetDisConn($dblink);
?>