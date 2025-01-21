<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//카테고리 목록

$arrCategory = getCategoryList_id("specification",$_GET["cat_no"]);//1차카테고리

$start = 0;
$end = 6;
$btnClick = 'addSpecificCat("#specification_'.$_GET["cat_no"].'",$("#t_ext_cat1").val(),$("#t_ext_cat1 option:selected")[0].innerText);';




SetDisConn($dblink);

?>
<table border="0" cellpadding="3" cellspacing="2" style="width:300px;">
	<form name="etcCatForm" id="etcCatForm">
	<input type="hidden" name="t_ext_cat_no" id="t_ext_cat_no">
	<tr>
		<td style="padding:5px 15px;">
			<select id="t_ext_cat1" name="t_ext_cat1" style="width: 250px;">
				<option value="">==========1차분류==========</option>
				<?php for($i=0;$i<$arrCategory["total"];$i++){?>
				<option value="<?=$arrCategory["list"][$i]['cat_no']?>"><?=$arrCategory["list"][$i]['cat_name']?></option>
				<?php } ?>
			</select>
		</td>
	</tr>

	<tr>
		<td style="padding:5px 15px;">
			<input type='button' value='입력' onclick='<?=$btnClick?>'>&nbsp;&nbsp;&nbsp;<input type='button' value='취소' onclick='LayerHideGoodCat();'>
		</td>
	</tr>
	</form>
</table>