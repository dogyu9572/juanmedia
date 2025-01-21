<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$cat_no = $_POST["cat_no"];

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrSubList = getCategoryList_id("industry", $cat_no);


for($i=0;$i<$arrSubList["total"];$i++){
?>
<li class="radio">
	<label><input type="radio" value="<?=$arrSubList["list"][$i]["cat_no"]?>" onclick="getProduct()" name="sub_cate"><i></i><p><?=$arrSubList["list"][$i]["cat_name"]?></p></label>
</li>
<?php
}

//DB해제
SetDisConn($dblink);
?>