<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();	// 전체카테고리

$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['goodidx']));
$relidxs = $arrInfo['list'][0]['rel_a_idx'];
$relidxsby = $arrInfo['list'][0]['rel_a_orderby'];

$arrRelidx = explode(",",$relidxs);
$arrRelidxBy = explode(",",$relidxsby);

if($_REQUEST['evnMode']=="P"){
	if($relidxs){		
		if (in_array($_REQUEST['relidx'], $arrRelidx)){
			## 해당 상품이 있으면 추가 안함
		}else{
			$relidxs .= ",".$_REQUEST['relidx'];
		}
	}else{
		$relidxs = $_REQUEST['relidx'];
	}
}else if($_REQUEST['evnMode']=="M"){	
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

$updateQuery = "update tbl_shop_good set rel_a_idx='".$relidxs."' where idx=".$arrInfo['list'][0]['idx'];
getFreeQueryCud($updateQuery);

$Query = "select * from tbl_board_academic WHERE idx IN (".$relidxs.") ORDER BY idx DESC";
$arrList = getFreeQueryR($Query);

//DB해제
SetDisConn($dblink);
if($arrList['list']['total']>0){
	for($i=0;$i<$arrList['list']['total'];$i++){	
?>
<tr>
	<td><?=$arrAllCategory[$arrList["list"][$i]['category']]?></td>
	<td style="text-align:left;"><strong><?=stripslashes($arrList['list'][$i]['subject'])?></strong></td>	
	<td><?=$arrList["list"][$i]['etc_2']?></td>
	<td><input type="text" class="w1" name="acdm_orderby[]" maxlength="5" value="<?=$arrRelidxBy[$i]?$arrRelidxBy[$i]:$arrList['list']['total']-$i?>" style="text-align:center;"></td>
	<td>
		<div class="btns">
			<button type="button" class="btn del" onclick="fnAcademicSelect('<?=$arrList['list'][$i]['idx']?>','M')">삭제</button>
		</div>
	</td>
</tr>
<?
	}
}else{
	echo "<tr><td colspan=\"5\">연결된 강의가 없습니다.</td></tr>";	
}
?>