<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//카테고리 목록

if($_GET['gb']=="a") {
	$arrCategory = getCategoryList(0);//1차카테고리

	$start = 0;
	$end = 6;
	$btnClick = "addGoodCat($(t_ext_cat_no).value);";
} else {
	$arrCategory = getCategoryList(103);//1차카테고리

	$start = 0;
	$end = $arrCategory["total"];
	$btnClick = "addGoodSearch($(t_ext_cat_no).value);";
}



SetDisConn($dblink);

?>
<table border="0" cellpadding="3" cellspacing="2" width="200">
	<form name="etcCatForm" id="etcCatForm">
	<input type="hidden" name="t_ext_cat_no" id="t_ext_cat_no">
	<tr>
		<td><select id="t_ext_cat1" name="t_ext_cat1" onchange="getExtCat1(this.value);">
		<option value="">==========1차분류==========</option>
		<?for($i=$start;$i<$end;$i++){?>
		<option value="<?=$arrCategory["list"][$i]['cat_no']?>"><?=$arrCategory["list"][$i]['cat_name']?></option>
		<?}?>
		</select></td>
	</tr>

	<?
	for($i=2;$i<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$i++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬 => 1차 초기화는 따로위에서 함
	?>
	<tr>
		<td><select name="t_ext_cat<?=$i?>" id="t_ext_cat<?=$i?>" onchange="getExtCat<?=$i?>(this.value);">
		<option value="">==========<?=$i?>차분류==========</option>
		</select>
		</td>
	</tr>
	<?
	}
	?>

	<tr>
		<td>
		<input type='button' value='입력' onclick='<?=$btnClick?>'>&nbsp;&nbsp;&nbsp;<input type='button' value='취소' onclick='LayerHideGoodCat();'>
		</td>
	</tr>
	</form>
</table>