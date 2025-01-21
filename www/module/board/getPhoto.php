<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$idx = mysqli_real_escape_string($dblink,$_POST["idx"]);

$arrBoardArticle = getBoardArticleView("photo", "", $idx,"read");

$imgsrc = "/pub/images/sub/img_depart46_role3.jpg";
for($i=0;$i<$arrBoardArticle["total_files"];$i++){
	if(substr($arrBoardArticle["files"][$i]["re_name"],0,2) == "l_"){
		$imgsrc = "/uploaded/board/photo/".$arrBoardArticle["files"][$i]["re_name"];
		break;
	}
}

//DB해제
SetDisConn($dblink);
?>
<p class="heading1"><?=$arrBoardArticle["list"][0]["subject"]?></p>
<div class="img"><img src="<?=$imgsrc?>" alt=""></div>