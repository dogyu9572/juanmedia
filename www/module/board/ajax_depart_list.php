<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$boardid = mysqli_real_escape_string($dblink,$_GET["boardid"]);

$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid);

$arrBoardList = getDepartSearchList(mysqli_real_escape_string($dblink,$_GET["category"]),mysqli_real_escape_string($dblink,$_GET['sk']),20, mysqli_real_escape_string($dblink,$_GET['offset']),'user');

//DB해제
SetDisConn($dblink);

for($i=0;$i<$arrBoardList["list"]["total"];$i++){
	if($arrBoardList["list"][$i]["re_name"] != ""){
		$bg_url = "/uploaded/board/".$boardid."/".$arrBoardList["list"][$i]["re_name"];
	}else{
		$bg_url = "";
	}
?>
	<a href="/depart/depart_11.php?idx=<?=$arrBoardList["list"][$i]["idx"]?>" class="depart">
		<span><?=$arrBoardList["list"][$i]["subject"]?></span><i class="ico1" <?php if($bg_url != ""){?>style="background-image: url('<?=$bg_url?>');"<?php } ?>></i>
	</a>
<?php
}
?>