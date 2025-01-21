<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['st']=="1"){
	$order_by = " A.sort_num DESC, A.idx DESC ";
}else{
	$order_by = " A.idx DESC ";
}

//상품 리스트
$arrList = getGoodListBaseNFile(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"");

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

//상품분류 리스트
$arrCategory = getCategoryList(0);//1차카테고리

$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

//카테고리 정보
$arrCatCode = explode("/", $arrCategoryInfo["list"][0]['cat_code']);

//DB해제
SetDisConn($dblink);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?></title>
<link href="/backoffice/css/style.css" rel="stylesheet" type="text/css" />
<script src="/common/js/common.js" type="text/javascript"></script>
<script src="/common/js/prototype-1.6.0.3-euc-kr.js" type="text/javascript"></script>
<script src="/common/js/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script src="/common/js/scriptaculous/effects.js" type="text/javascript"></script>
<script src="/common/js/layer.js" type="text/javascript"></script>
<script src="/common/js/shop.js" type="text/javascript"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<script language="javascript">
<?
for($j=1;$j<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$j++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬
?>
//카테고리 초기화
function initCat<?=$j?>(){
	for(i=$("cat<?=$j?>").length; i >= 0; i--){
		$("cat<?=$j?>").options[i] = null;
	}
	$("cat<?=$j?>").options[0] = new Option("=====<?=$j?>차분류=====","");
}

//카테고리 가져오기
function getCat<?=$j?>(cat,selected_idx){
	//선택된 값 이후 카테고리는 초기화
	//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
	<?for($k=$j;$k<$_SITE["PRODUCT"]["CATEGORY_DEPTH"];$k++){?>
	try{ initCat<?=$k+1?>(); }catch(e){}
	<?}?>

	new Ajax.Request('/module/category/ajax_get_cat.php',
	{
		method: 'get',
		asynchronous: this.asynchronous,
		contentType: 'application/x-www-form-urlencoded',
		encoding: 'euc-kr',
		parameters: {cat_no: cat},

		onSuccess: function(transport){
			var response = transport.responseText; 
			setCat<?=$j?>(response,selected_idx);
			//카테고리 번호 설정
			$("cat_no").value = cat;
		},
		
		onFailure: function(){ 
			alert('Something went wrong...') 
		}   
	});

}

//카테고리 설정하기
function setCat<?=$j?>(txt,selected_idx){
	if(txt !=""){
		var opt = new Array();
		var opt2 = new Array();
		opt = txt.split("||");
		for(i=0; i<opt.length; i++){
			opt2 = opt[i].split("**");
			//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
			try{
				if(selected_idx==opt2[0]){
					$("cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0],true,true);
				}else{
					$("cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0]);
				}
			}catch(e){}
		}
	}
}
<?
} //카테고리 js 끝
?>

function setCode(idx, name){
	frm = opener.parent.document.form2;
	frm.etc_1.value = idx;
	frm.etc_2.value = name;
	opener.parent.document.getElementById("tmp_etc_2").innerHTML = name;
	self.close();

}
</script>

<script language="javascript">

</script>
<table border="0" width="100%" cellpadding="3" cellspacing="0" align="center">
	<form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
	<tr>
		<td><input type="button" value="창닫기" style="color:red" onclick="self.close();"></td>
	</tr>
	<tr>
		<td>
			<fieldset>
			<legend>상품검색</legend>
				<input type="hidden" name="cat_no" id="cat_no">
				<table border="0" cellpadding="3" cellspacing="2" width="100%">
				<tr><td>
				<select name="cat" id="cat" onchange="getCat1(this.value);">
				<option value="">==========1차분류==========</option>
				<?for($i=0;$i<$arrCategory["total"];$i++){?>
				<option value="<?=$arrCategory["list"][$i]['cat_no']?>"<?=$arrCatCode[0]==$arrCategory["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory["list"][$i]['cat_name']?></option>
				<?}?>
				</select>

				<?
				for($i=2;$i<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$i++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬 => 1차 초기화는 따로위에서 함
				?>
				<select name="cat<?=$i?>" id="cat<?=$i?>" onchange="getCat<?=$i?>(this.value);">
				<option value="">==========<?=$i?>차분류==========</option>
				</select>
				<?
				}
				?>
				<input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input">
				<select name="st">
				<option value="0"<?=$_REQUEST['st']=="0"?" selected":""?>>상품번호 역순</option>
				<option value="1"<?=$_REQUEST['st']=="1"?" selected":""?>>정렬번호 역순</option>
				</select>
				<input type="submit" value="검색" style="width:60px;height:22px">
				</td></tr>
				</table>
			</fieldset>
		</td>
  </tr>
	</form>
</table>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
<form name="frmRelGood" method="get" action="<?=$_SERVER['PHP_SELF']?>">
<tr height="25" align="center" bgcolor="#646464">
  <td width="5%"><font color="#ffffff">선택</font></td>
  <td width="5%"><font color="#ffffff">사진</font></td>
  <td width="30%"><font color="#ffffff">상품분류</font></td>
  <td width="40%"><font color="#ffffff">상품명</font></td>
  <td width="10%"><font color="#ffffff">판매가</font></td>
</tr>
<?if($arrList['list']['total'] > 0):?>

<?for ($i=0;$i<$arrList['list']['total'];$i++) {
	//카테고리 정보
	$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);
?>
<tr height="25" align="center">
	<td><input type="radio" id="items" name="items" value="<?=$arrList['list'][$i]['idx']?>" onclick="setCode('<?=$arrList['list'][$i]['idx']?>','<?=$arrList['list'][$i]['g_name']?>');"></td>
	<td><img src="/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['image_s']?>" width="60"></td>
	<td align="left">
	<?for($j=0; $j <count($arrThisCatCode)-1;$j++){?>
	<?=$arrAllCategory[$arrThisCatCode[$j]]?>
	<?=($j < count($arrThisCatCode)-2)?">":""?>
	<?}?>
	</td>
	<td align="left"><?=stripslashes($arrList['list'][$i]['g_name'])?></td>
	<td><?=number_format($arrList['list'][$i]['price'])?></td>
</tr>
<tr>
  <td colspan="12" height="1" bgcolor="646464"></td>
</tr>
<?}?>

<?else:?>
<tr height="100" align="center">
  <td width="100%" colspan="12" >등록된 상품이 없습니다.</td>
</tr>
<tr>
  <td colspan="12" height="1" bgcolor="646464"></td>
</tr>
<?endif;?>
</form>
</table>

<br />
<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],"cat_no=".$_REQUEST['cat_no']."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk'])?>
</div>

<script language="javascript">
<?
for($i=0;$i<count($arrCatCode)-1;$i++){
?>
getCat<?=$i+1?>('<?=$arrCatCode[$i]?>','<?=$arrCatCode[$i+1]?>');
<?
}
?>
</script>

</body>
</html>
