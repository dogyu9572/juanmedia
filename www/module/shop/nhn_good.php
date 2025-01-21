<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();


$query = $_SERVER['QUERY_STRING']; 
$vars = array(); 
foreach(explode('&', $query) as $pair) {
	list($key, $value) = explode('=', $pair);
	$key = urldecode($key);
	$value = urldecode($value);
	$vars[$key][] = $value; 
}

$itemIds = $vars['ITEM_ID'];

if (count($itemIds) < 1) { 
	exit('ITEM_ID 는 필수입니다.'); 
}

header('Content-Type: application/xml;charset=utf-8'); 
echo ('<?xml version="1.0" encoding="utf-8"?>'); 
?>

<response>
<?
for($i=0; $i<count($itemIds); $i++) {
	
	$arrInfo[$i] = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $itemIds[$i]));

	//카테고리 정보
	$arrCatCode[$i] = explode("/", $arrInfo[$i]["list"][0]["cat_code"]);


	$id = $arrInfo[$i]["list"][0]["idx"]; 
	$name = stripslashes($arrInfo[$i]["list"][0]['g_name']); 
	$description = stripslashes(strip_tags($arrInfo[$i]["list"][0]['contents']));
	 if($arrInfo[$i]["list"][0]['sale_price']) {
		$price = $arrInfo[$i]["list"][0]['sale_price']; 
	}
	if($arrInfo[$i]["list"][0]['bundle_price']) {
		$price = $arrInfo[$i]["list"][0]['bundle_price']; 
	}
	if($arrInfo[$i]["list"][0]['price']) { 
		$price = $arrInfo[$i]["list"][0]['price']; 
	}
	$quantity = 99; 
?>
	<item id="<?=$id?>"> 
		<name><![CDATA[<?=$name?>]]></name> 
		<url><![CDATA[http://<?=$_SERVER["HTTP_HOST"]?>/shop.php?goPage=GoodDetail&g_code=<?=$arrInfo[$i]["list"][0]['g_code']?>]]></url> 
		<description><![CDATA[ <?=$description?> ]]></description> 
		<image>http://<?=$_SERVER["HTTP_HOST"]?>/uploaded/shop_good/<?=$arrInfo[$i]["list"][0]['idx']?>/<?=$arrInfo[$i]["list"][0]['p_image']?></image> 
		<thumb>http://<?=$_SERVER["HTTP_HOST"]?>/uploaded/shop_good/<?=$arrInfo[$i]["list"][0]['idx']?>/<?=$arrInfo[$i]["list"][0]['image_s']?></thumb> 
		<price><?=$price?></price> 
		<quantity><?=$quantity?></quantity> 
		<category> 
			<first id="<?=$arrCatCode[$i]['0']?>"><?=$arrAllCategory[$arrCatCode[$i]['0']]?></first> 
			<second id="<?=$arrCatCode[$i]['1']?>"><?=$arrAllCategory[$arrCatCode[$i]['1']]?></second> 
			<? if($arrCatCode[2]) {?>
			<third id="<?=$arrCatCode[$i]['2']?>"><?=$arrAllCategory[$arrCatCode[$i]['2']]?></third> 
			<?}?>
		</category> 
		
		<?
		if($arrInfo[$i]["total_opt"] > 0){
			for($k=0;$k<$arrInfo[$i]["total_opt"];$k++){
		?>
		<options> 
			<option name="<?=stripslashes($arrInfo[$i]["opt"][$k]["opt_1"])?>"> 
				<?
				for($j=0;$j<$arrInfo[$i]["total_opt_info"];$j++){
				if($arrInfo[$i]["opt"][$i]["opt_1"]==$arrInfo[$i]["opt_info"][$j]["opt_1"]){
				?>
				<select> <![CDATA[ <?=stripslashes($arrInfo[$i]["opt_info"][$j]["opt_1_value"])?> ]]> </select> 
				<?}}?>		
			</option>
		</options>
		<?}}?>
	</item> 
<? 
}

//DB해제
SetDisConn($dblink);

echo('</response>'); 
?>
