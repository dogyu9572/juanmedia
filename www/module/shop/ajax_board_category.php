<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$stepNum = $_POST['snum'];

$arrCategory = getCategoryList($_POST['cat_no']);

SetDisConn($dblink);

?>
<select name="category" style="width:240px;">
<?
for($i=0;$i<$arrCategory["total"];$i++){		
?>
	<option value="<?=$arrCategory["list"][$i]['cat_no']?>" <?=$selected[$i]?>><?=$arrCategory["list"][$i]['cat_name']?></option>
<?
}
?>
</select>