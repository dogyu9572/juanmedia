<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
########################################################## 의료진 검색 ST
$arrBoardDoctorList = getBoardListBase("trmdoctor",$_REQUEST["category"]);

echo '<select name="etc_3" id="wr02" class="text w40p"><option value="">담당의사를 선택해주세요.</option>';
for($i=0; $i < $arrBoardDoctorList["list"]["total"]; $i++){
?>
	<option value="<?=$arrBoardDoctorList['list'][$i]['name']?>"><?=$arrBoardDoctorList['list'][$i]['name']?> <?php if($arrBoardDoctorList['list'][$i]['name']=="이종표"){?>원장<?}else{?>과장<?}?></option>
<?
}
echo '</select>';
########################################################## 의료진 검색 ED

//DB해제
SetDisConn($dblink);
?>