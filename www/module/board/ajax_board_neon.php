<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
########################################################## 진료대기현황 검색 ST
$arrBoardList = getBoardListBase("trmneon",$_REQUEST["category"]);

echo '<div class="tt">'.$_REQUEST["category"].'진료실<p><!--홍길동 원장님--></p></div>';
for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
	if($arrBoardList["list"][$i][subject]==$_REQUEST["usernum"]){$thisClass = "num on";}else{$thisClass = "num";}
?>
	<p class="<?=$thisClass?>"><?=$arrBoardList["list"][$i][subject]?></p>
<?
}
echo '';
########################################################## 진료대기현황 검색 ED

//DB해제
SetDisConn($dblink);
?>