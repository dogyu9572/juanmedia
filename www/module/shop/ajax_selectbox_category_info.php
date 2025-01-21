<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$stepNum = $_POST['snum'];

$arrCategory = getCategoryList($_POST['cat_no'],"Y");

SetDisConn($dblink);

?>
<select name="cat<?=$stepNum?>" id="cat<?=$stepNum?>" onchange="fnCat<?=$stepNum?>(this.value);" style="height:100px;" size="3">
	<option value="">======<?=$stepNum?>차분류======　　</option>
	<?
	for($i=0;$i<$arrCategory["total"];$i++){
		$viewflag[$i] = true;
		if($stepNum=="2"){
			if($_POST['eventMd']=="Y"){	## 행사상품
				if($arrCategory["list"][$i]['cat_no']=="11" || $arrCategory["list"][$i]['cat_no']=="17"){
					$viewflag[$i] = true;
					$selected[$i] = "selected";
				}else{
					$viewflag[$i] = false;
				}
			}else{
				if($arrCategory["list"][$i]['cat_no']=="11" || $arrCategory["list"][$i]['cat_no']=="17"){
					$viewflag[$i] = false;
				}else{
					$viewflag[$i] = true;
				}
			}			
		}
		if($viewflag[$i]){
	?>
	<option value="<?=$arrCategory["list"][$i]['cat_no']?>" <?=$selected[$i]?>><?=$arrCategory["list"][$i]['cat_name']?></option>
	<?
		}
	}
	?>
</select>