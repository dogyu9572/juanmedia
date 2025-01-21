<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/zipcode/zipcode.lib.php";

//DB연결
$dblink = SetConn($_conf_db["zipcode"]);

//카테고리 목록
$arrList = getZipCodeGugun("_".$_REQUEST["sido"]);

//DB해제
SetDisConn($dblink);

for($i=0;$i<$arrList["total"];$i++){
	echo $arrList["list"][$i][gugun] . "**" . $arrList["list"][$i][gugun];
	if($i != ($arrList["total"]-1)){
		echo "||";
	}
}
?>