<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrBoardArticle = getBoardArticleView($_REQUEST["boardid"], $_REQUEST["category"], $_REQUEST["g_idx"],"read");

//DB해제
SetDisConn($dblink);

?>
<p id="bdr_subject"><?=stripslashes($arrBoardArticle["list"][0][subject])?></p>
<table>
	<tbody>
		<tr>
			<th>세미나 일시</th>
			<td id="bdr_date"><?=stripslashes($arrBoardArticle["list"][0][schedule_date])?> <?=substr($arrBoardArticle["list"][0][etc_1],0,2)?>:<?=substr($arrBoardArticle["list"][0][etc_1],2,2)?> ~ <?=substr($arrBoardArticle["list"][0][etc_2],0,2)?>:<?=substr($arrBoardArticle["list"][0][etc_2],2,2)?></td>
		</tr>
		<tr>
			<th>세미나 장소</th>
			<td id="bdr_place"><?=stripslashes($arrBoardArticle["list"][0][homepage])?></td>
		</tr>
	</tbody>
</table>
<div class="detailCont" id="bdr_contents">
	<?=stripslashes($arrBoardArticle["list"][0][contents])?>
</div>
<div class="text-right">
	<a class="blackBtn text-center" href="/news/seminar.php">상세내용보기</a>
</div>