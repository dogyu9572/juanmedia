<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$lang = mysqli_real_escape_string($dblink,$_GET["lang"]);

$idx = mysqli_real_escape_string($dblink,$_GET["idx"]);

$arrBoardList = getStaffBreakListNUser($idx);

//DB해제
SetDisConn($dblink);

if($arrBoardList["total"] > 0){
	for($i=0;$i<$arrBoardList["total"];$i++){
?>
	<tr>
		<td><?=$arrBoardList["list"][$i]["schedule_date"]?></td>
		<td><?=$arrBoardList["list"][$i]["etc_1"] == "Y"?"O":"X"?></td>
		<td><?=$arrBoardList["list"][$i]["etc_2"] == "Y"?"O":"X"?></td>
		<td><?=$lang !=""?$arrBoardList["list"][$i]["etc_5"]:$arrBoardList["list"][$i]["contents"]?></td>
	</tr>
<?php
	}
}else{
	if($lang !=""){
?>
<tr>
	<td colspan="4">There is no closure/closing information.</td>
</tr>
<?php
	}else{
?>
<tr>
	<td colspan="4">휴진 / 마감 정보가 없습니다.</td>
</tr>
<?php
	}
}
?>