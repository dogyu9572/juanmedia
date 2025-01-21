<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$sub_cate = $_POST["sub_cate"];
$arrOs = $_POST["os"];

$boardid = "product";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
	$arrProductCategory = getCategoryNo1_id("product");
	for($i=0;$i<$arrProductCategory["total"];$i++){
		$arrProduct[$arrProductCategory["list"][$i]["cat_no"]] = $arrProductCategory["list"][$i]["cat_name"];
	}

	$arrBCategoryList = intersectBoardCategory($sub_cate,$arrOs);
	
	$arridx = array();
	$arridx[0] = "0";
	for($i=0;$i<$arrBCategoryList["total"];$i++){
		$arridx[$i+1] = $arrBCategoryList["list"][$i]["b_idx"];
	}

	$arrCategory = array(2,4,6,5,8,7,10); // 고객사요구사항...

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
	<div class="stt">검색결과 <span class="fw600"><?=number_format($arrList["total"])?></span>건</div>
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
	for($j=0;$j<count($arrCBList);$j++){
?>
	<div class="search_function">
		<h1><?=$arrProduct[$arrCategory[$j]]?></h1>
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