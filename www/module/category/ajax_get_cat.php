<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//카테고리 목록
$arrList = getCategoryList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));

//DB해제
SetDisConn($dblink);

for($i=0;$i<$arrList["total"];$i++){
	echo $arrList["list"][$i]['cat_no'] . "**" . $arrList["list"][$i]['cat_name'];
	if($i != ($arrList["total"]-1)){
		echo "||";
	}
}
?>