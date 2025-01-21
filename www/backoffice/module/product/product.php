<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/product/product.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST[st]=="1"){
	$order_by = " A.sort_num DESC, A.idx DESC ";
}else{
	$order_by = " A.idx DESC ";
}

//제품 리스트
$arrList = getProductListBaseNFile(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[cat_no]), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sw]), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sk]), 
	$scale, $_REQUEST[offset]);

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

//제품분류 리스트
$arrCategory = getCategoryList(0);//1차카테고리

$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[cat_no]));

//카테고리 정보
$arrCatCode = explode("/", $arrCategoryInfo["list"][0][cat_code]);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">제품 관리</h2>

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
</script>

<script language="javascript">
function delProduct(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 제품을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}
</script>

<h3 class="admin-title-middle">제품검색</h3>
<form name="frmSort" method="get" action="<?=$_SERVER[PHP_SELF]?>" style="position:relative;">
<input type="hidden" name="cat_no" id="cat_no">
<table class="admin-table-type1" summary="제품검색">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
    <tr>
	  <th>분류선택</th>
	  <td class="space-left">
	    <select name="cat" id="cat" onchange="getCat1(this.value);">
		<option value="">==========1차분류==========</option>
		<?for($i=0;$i<$arrCategory["total"];$i++){?>
		<option value="<?=$arrCategory["list"][$i][cat_no]?>"<?=$arrCatCode[0]==$arrCategory["list"][$i][cat_no]?" selected":""?>><?=$arrCategory["list"][$i][cat_name]?></option>
		<?}?>
		</select>
		<?for($i=2;$i<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$i++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬 => 1차 초기화는 따로위에서 함?>
		<select name="cat<?=$i?>" id="cat<?=$i?>" onchange="getCat<?=$i?>(this.value);">
		<option value="">==========<?=$i?>차분류==========</option>
		</select>
		<?}?>
	  </td>
	</tr>
	<tr>
	  <th>정렬</th>
	  <td class="space-left">
	    <select name="st">
		<option value="0"<?=$_REQUEST[st]=="0"?" selected":""?>>제품번호 역순</option>
		<option value="1"<?=$_REQUEST[st]=="1"?" selected":""?>>정렬번호 역순</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <th>제품명</th>
	  <td class="space-left"><input type="text" name="sk" value="<?=$_REQUEST[sk]?>" class="input" style="width:300px;" /> <input type="image" src="/backoffice/images/btn_search.gif" alt="검색"/></td>
	</tr>
  </tbody>
</table>
</form>

<br />
<br />

<div class="mgb5">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
<table class="admin-table-type1">
<thead>
<tr>
  <th width="50">No.</th>
  <th width="70">사진</th>
  <th width="200">제품분류</th>
  <th width="*">제품명</th>
  <th width="60">메인표시</th>
  <th width="60">정렬번호</th>
  <th width="120">등록일</th>
  <th width="80">관리</th>
</tr>
</thead>
<tbody>
<?if($arrList['list']['total'] > 0):?>

<?for ($i=0;$i<$arrList['list']['total'];$i++) {
	//카테고리 정보
	$arrThisCatCode = explode("/", $arrList["list"][$i][cat_code]);	
?>
<tr>
	<td><?=$arrList['list'][$i]['idx']?></td>
	<td><a href="/product/product_detail.php?idx=<?=$arrList['list'][$i]['idx']?>" target="_blank"><img src="/uploaded/product/<?=$arrList['list'][$i]['idx']?>/s_<?=$arrList['list'][$i]['p_image']?$arrList['list'][$i]['p_image']:$arrList['list'][$i]['re_name']?>" width="70"></a></td>
	<td align="left">
	<?for($j=0; $j <count($arrThisCatCode)-1;$j++){?>
	<?=$arrAllCategory[$arrThisCatCode[$j]]?>
	<?=($j < count($arrThisCatCode)-2)?" > ":""?>
	<?}?>
	</td>
	<td class="space-left"><?=$arrList['list'][$i]['p_name']?></td>
	<td><?=$arrList['list'][$i]['show_main']?></td>
	<td><?=$arrList['list'][$i]['sort_num']?></td>
	<td><?=$arrList['list'][$i]['wdate']?></td>
	<td class="b02"><a href="product_info.php?idx=<?=$arrList['list'][$i]['idx']?>&listURL=<?=urlencode($_SERVER[REQUEST_URI])?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a> <a href="javascript:delProduct('<?=$arrList['list'][$i]['idx']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
</tr>
<?}?>

<?else:?>
<tr height="100" align="center">
  <td colspan="12" >등록된 제품이 없습니다.</td>
</tr>
<?endif;?>
</tbody>
</table>

<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$_GET[offset],"cat_no=".$_REQUEST[cat_no]."&sw=".$_REQUEST[sw]."&sk=".$_REQUEST[sk])?>
</div>

<form name="frmListHidden" method="post" action="product_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
</form>


<script language="javascript">
<?
for($i=0;$i<count($arrCatCode)-1;$i++){
?>
getCat<?=$i+1?>('<?=$arrCatCode[$i]?>','<?=$arrCatCode[$i+1]?>');
<?
}
?>
</script>
  </div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
