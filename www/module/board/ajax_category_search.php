<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_POST["cat_no"]){
	//카테고리 목록
	$arrList = getCategoryList(mysqli_real_escape_string($GLOBALS['dblink'], $_POST["cat_no"]));
}
//DB해제
SetDisConn($dblink);
echo '<select name="search_sub_category" id="search_sub_category" style="width:99%;" class="select" onchange="fnSearchCourse(\'\',this.value)"><option value="">▼ 소분류</option>';
for($i=0;$i<$arrList["total"];$i++){
	if($_POST['sub_cat_no']==$arrList["list"][$i]['cat_no']){
		$optionSelect = "selected";
	}else{
		$optionSelect = "";	
	}
	echo '<option value="'.$arrList["list"][$i]['cat_no'].'" '.$optionSelect.'>'.$arrList["list"][$i]['cat_name'].'</option>';	
}
echo '</select>';
?>