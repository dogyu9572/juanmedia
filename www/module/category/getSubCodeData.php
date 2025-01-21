<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

	$gender = mysqli_real_escape_string($dblink, $_POST["gender"]) == "M"?"M":"W";
	
	$arrOneCodeList = getCategoryDepthList_id("code","1");

	$arrCodeList = getCategoryDepthList_id("code","2");

	$arrBoardArticle = getBoardArticleView("inspection", "", mysqli_real_escape_string($dblink, $_POST["idx"]),"view");

	$arrEtc_5 = explode(",",$arrBoardArticle["list"][0]["etc_5"]);

	$arrFirstCode = array();
	$arrCode = array();

	for($i=0;$i<$arrOneCodeList["total"];$i++){
		$arrFirstCode[$arrOneCodeList["list"][$i]["cat_no"]] = $arrOneCodeList["list"][$i]["cat_name"];
	}

	for($i=0;$i<$arrCodeList["total"];$i++){
		if(in_array($arrCodeList["list"][$i]["cat_no"],$arrEtc_5)){
			$arrCode[$arrCodeList["list"][$i]["cat_parent_no"]][$arrCodeList["list"][$i]["cat_no"]] = $arrCodeList["list"][$i];
		}
	}

//DB해제
SetDisConn($dblink);
?>
<dl class="head">
	<dt>검사명</dt>
	<dd>검사항목</dd>
</dl>
<?php foreach($arrCode as $key => $arrSubList){?>
<dl>
	<dt><?=$arrFirstCode[$key]?></dt>
	<dd class="t-left">
		<div class="chk-wrap">
			<?php foreach($arrSubList as $subkey => $arrList){?>
			<p class="checkbox">
				<input type="checkbox" name="sub_price[]" id="sub_price_<?=$arrList["cat_no"]?>" value="<?=$gender == "M"?$arrList["cat_man_price"]:$arrList["cat_woman_price"]?>" onclick="setTotalPrice()">
				<label for="sub_price_<?=$arrList["cat_no"]?>"><?=$arrList["cat_name"]?> [<?=number_format($gender == "M"?$arrList["cat_man_price"]:$arrList["cat_woman_price"])?>원]</label>
			</p>
			<?php } ?>
		</div>
	</dd>
</dl>
<?php } ?>