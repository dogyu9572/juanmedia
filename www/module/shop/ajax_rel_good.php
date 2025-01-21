<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['goodidx']));
$relidxs = $arrInfo['list'][0]['rel_g_idx'];

if($_REQUEST['evnMode']=="P"){
	if($relidxs){
		$relidxs .= ",".$_REQUEST['relidx'];
	}else{
		$relidxs = $_REQUEST['relidx'];
	}
}else if($_REQUEST['evnMode']=="M"){
	$arrRelidx = explode(",",$relidxs);
	$relidxs = "";
	$comma = "";
	for($i=0;$i<count($arrRelidx);$i++){
		if($arrRelidx[$i]!=$_REQUEST['relidx']){
			$relidxs .= $comma.$arrRelidx[$i];
			$comma = ",";
		}
	}
}else{
	#### 관련상품 보기
}

$updateQuery = "update tbl_shop_good set rel_g_idx='".$relidxs."' where idx=".$arrInfo['list'][0]['idx'];
getFreeQueryCud($updateQuery);

$Query = "select * from tbl_shop_good WHERE idx IN (".$relidxs.") ORDER BY idx DESC";
$arrList = getFreeQueryR($Query);

//DB해제
SetDisConn($dblink);

for($i=0;$i<$arrList['list']['total'];$i++){
	$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);
	if($arrList['list'][$i]['image_s']) {
		$simg = "<img src=\"/uploaded/shop_good/".$arrList['list'][$i]['idx']."/".$arrList['list'][$i]['image_s']."\">";
	} else {
		$simg = "";
	}
	$arrOption[$i] = str_replace("|",", ", $arrList["list"][$i]['option_title']);
?>
<tr>
	<td><?=stripslashes($arrList['list'][$i]['g_code'])?></td>
	<td><?=$simg?></td>
	<td style="text-align:left;"><strong><?=stripslashes($arrList['list'][$i]['g_name'])?></strong><br/><?=$arrOption[$i]?></td>	
	<td>
		<div class="btns">
			<button type="button" class="btn del" onclick="fnGoodSelect('<?=$arrList['list'][$i]['idx']?>','M')">삭제</button>
		</div>
	</td>
</tr>
<?}?>