<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//카테고리 목록
$arrList = getCategoryList_id(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["categoryid"]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]),"Y");


for($i=0;$i<$arrList["total"];$i++){
	$arrRowList = getCategoryList_id(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["categoryid"]),mysqli_real_escape_string($GLOBALS['dblink'], $arrList["list"][$i]["cat_no"]),"Y");
	if($arrRowList["total"] > 0){
		for($j=0;$j<$arrRowList["total"];$j++){
			$selected = false;
			if($arrRowList["list"][$j]["cat_no"] == $_REQUEST["selected"]){
				$selected = true;
			}
			echo "<option value='".$arrRowList["list"][$j]["cat_no"]."' ".($selected?"selected":"").">".$arrList["list"][$i]["cat_name"]." > ".$arrRowList["list"][$j]["cat_name"]."</option>";
		}
	}else{
		$selected = false;
		if($arrList["list"][$i]["cat_no"] == $_REQUEST["selected"]){
			$selected = true;
		}
		echo "<option value='".$arrList["list"][$i]["cat_no"]."' ".($selected?"selected":"").">".$arrList["list"][$i]["cat_name"]."</option>";
	}
}

//DB해제
SetDisConn($dblink);
?>