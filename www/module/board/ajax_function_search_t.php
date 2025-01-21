<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$cat_no = $_GET["cat_no"];

$boardid = "product";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

	$arrProductCategory = getCategoryNo1_id("product");
	for($i=0;$i<$arrProductCategory["total"];$i++){
		$arrProduct[$arrProductCategory["list"][$i]["cat_no"]] = $arrProductCategory["list"][$i]["cat_name"];
	}

	$arrBCategoryList = getFreeArticleList("tbl_board_category", 0, 0, " where categoryid = 'function' and cat_no in (".$cat_no.") GROUP BY b_idx HAVING txt_cat_no = '".$cat_no."' ", " DISTINCT b_idx, GROUP_CONCAT(cat_no ORDER BY b_order ASC) AS txt_cat_no ");

	var_dump($arrBCategoryList);
	
	$arridx = array();
	for($i=0;$i<$arrBCategoryList["total"];$i++){
		$arridx[$i] = $arrBCategoryList["list"][$i]["b_idx"];
	}
	if($cat_no != ""){
		$arridx[] = "0";
	}

	$arrCategory = array(2,4,6,5,8,7,10); // 고객사요구사항...

	$arrTmpCategory = array();

	for($i=0;$i<count($arrCategory);$i++){
		if($arrCategory[$i] == 10){
			$arrCBList[$i][0] = getBoardListBaseNFile($boardid, $arrCategory[$i], "arridx", implode(",",$arridx), 0, 0, "user");
		}else{
			$arrCategoryList = getCategoryNo1_id("product",$arrCategory[$i]);
			$arrTmpCategory[$i] = array();
			for($j=0;$j<$arrCategoryList["total"];$j++){
				$arrCBList[$i][$j] = getBoardListBaseNFile($boardid, $arrCategoryList["list"][$j]["cat_no"], "arridx", implode(",",$arridx), 0, 0, "user");
			}
		}
	}


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
<?php 
	for($j=0;$j<count($arrCBList);$j++){
?>
	<div class="search_function">
		<h1 class="c_title"><?=$arrProduct[$arrCategory[$j]]?></h1>
		<ul>
		<?php
		for($k=0;$k<count($arrCBList[$j]);$k++){
			$arrList = $arrCBList[$j][$k];
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
		}
		?>
		</ul>
	</div>
<?php 
	} 
?>
<script type="text/javascript">
//<![CDATA[
$(document).ready (function () {
	$(".top .checkbox").click(function(){
		$(".btn_check").toggleClass('on');
	});
});

</script>