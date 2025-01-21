<?
header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//카테고리 목록
$arrList = getOptRelInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["idx"]), mysqli_real_escape_string($GLOBALS['dblink'], iconv("UTF-8","EUC-KR",$_REQUEST["opt_1"])));

//DB해제
SetDisConn($dblink);

for($i=0;$i<$arrList["total"];$i++){
	echo $arrList["list"][$i]['opt_2_value'] . "**" . $arrList["list"][$i]['price'] . "**" . $arrList["list"][$i]['stock'];
	if($i != ($arrList["total"]-1)){
		echo "||";
	}
}
?>