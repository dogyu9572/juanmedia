<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$arrIdx = $_POST["arrIdx"];

$mode = $_POST["mode"] == ""?"0":$_POST["mode"];

if($arrIdx == ""){
	$arrIdx = '0';
}

$boardid = "product";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrspecification = getCategoryList_id("specification","");

$arrList = getBoardListBaseNFile($boardid, "", "arridx", $arrIdx, 0, 0, "user");
							


for($i=0;$i<$arrList["total"];$i++){
	$imgsrc[$i] = "/uploaded/board/".$boardid."/".$arrList["list"][$i]['re_name'];
	if(!$arrList["list"][$i]['re_name']){$imgsrc[$i] = "/pub/images/no_image.svg";}

	$arrSpecificationList = getBoardCategoryList($boardid,$arrList["list"][$i]['idx'],"specification");

	
?>
	<div class="swiper-slide" id="product<?=$mode != "0"?"":"_hnb"?>_<?=$arrList["list"][$i]["idx"]?>">
		<div class="tab_wrap dpf" >
			<div class="saira tab"><?=$arrList["list"][$i]["subject"]?></div>
			<i class="close" onclick="changeCheck('<?=$arrList["list"][$i]["idx"]?>')"></i>
		</div>
		<?php if($mode != "0"){?>
		<ul>
			<li class="l1"><img src="<?=$imgsrc[$i]?>" alt="image"></li>
		<?php
			$num = 2;
			for($k=0;$k<$arrspecification["total"];$k++){
		?>
			<li class="l<?=$num?>">
		<?php
				for($j=0;$j<$arrSpecificationList["total"];$j++){
					$arrTmpInfo = getCategoryInfo_id('specification', $arrSpecificationList["list"][$j]["cat_no"]);
					if($arrTmpInfo["list"][0]["cat_parent_no"] == $arrspecification["list"][$k]["cat_no"]){
		?>
						<p>· <?=$arrTmpInfo["list"][0]["cat_name"]?></p>
		<?php 
					}
				}
		?>
			</li>
		<?php
				$num++;
			}
		?>
		</ul>
		<?php } ?>
	</div>
<?php
}
//DB해제
SetDisConn($dblink);
?>