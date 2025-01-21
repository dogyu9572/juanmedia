<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$cat_no = $_POST["cat_no"];

$boardid = "product";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

	$arrBCategoryList = getFreeArticleList("tbl_board_category", 0, 0, " where categoryid = 'function' and cat_no in (".$cat_no.") ", " DISTINCT b_idx ");
	
	$arridx = array();
	for($i=0;$i<$arrBCategoryList["total"];$i++){
		$arridx[$i] = $arrBCategoryList["list"][$i]["b_idx"];
	}

	$arrList = getBoardListBaseNFile($boardid, "", "arridx", implode(",",$arridx), 0, 0, "user");


//DB해제
SetDisConn($dblink);

?>
<div class="top">
	<div class="con_right">
		<div class="checkbox">
			<label>
				<a class="btn btn_check ">제품 전체 선택<input type="checkbox" id="check_all" name="check_all" onchange="checkAll()"><i></i></a>
			</label>
		</div>
	</div>
</div>
<ul>
<?php
	for($i=0;$i<$arrList["total"];$i++){
		$imgsrc[$i] = "/uploaded/board/".$boardid."/".$arrList["list"][$i]['re_name'];
		if(!$arrList["list"][$i]['re_name']){$imgsrc[$i] = "/pub/images/no_image.png";}
?>
	<li class="checkbox">
		<label>
			<div class="img">
				<input type="checkbox" name="idx[]" id="idx_<?=$arrList["list"][$i]['idx']?>" value="<?=$arrList["list"][$i]['idx']?>" <?php if(in_array($arrList["list"][$i]['idx'],$_SESSION[$_SITE["DOMAIN"]]["CART"])){?>checked<?php } ?> onclick="changeCheck(this.value)"><i></i>
				<img src="<?=$imgsrc[$i]?>" alt="image">
			</div>
			<div class="txt_box">
				<div class="tt"><?=$arrList["list"][$i]['subject']?></div>
				<div class="stt"><?=$arrList["list"][$i]['etc_1']?></div>
				<div class="stt"><?=$arrList["list"][$i]['etc_2']?></div>
				<a href="/product_search/product_comparison.php?idx=<?=$arrList["list"][$i]['idx']?>" class="btn btn_gradient">상세정보</a>
			</div>
		</label>
	</li>
<?php 
	} 
?>
</ul>
<script type="text/javascript">
//<![CDATA[
$(document).ready (function () {
	$(".top .checkbox").click(function(){
		$(".btn_check").toggleClass('on');
	});
});

</script>