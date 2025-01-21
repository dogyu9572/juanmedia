<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/product/product.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/fckeditor/fckeditor.php";
if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//제품분류 리스트
$arrCategory = getCategoryList(0);//1차카테고리

//_DEBUG($arrList);
//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">제품 등록</h2>

<script language="javascript">
function CheckForm(frm){
	if (frm.cat_no.value==""){
		alert("제품 분류를 선택해 주십시요.");
		frm.cat.focus();
		return false;
	}
	if (frm.p_name.value==""){
		alert("제품명을 입력해 주십시요.");
		frm.p_name.focus();
		return false;
	}
}


<?
for($j=1;$j<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$j++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬
?>
//카테고리 초기화
function initCat<?=$j?>(){
	for(i=$("cat<?=$j?>").length; i >= 0; i--){
		$("cat<?=$j?>").options[i] = null;
	}
	$("cat<?=$j?>").options[0] = new Option("==========<?=$j?>차분류==========","");
}

//카테고리 가져오기
function getCat<?=$j?>(cat){
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
			setCat<?=$j?>(response);
			//카테고리 번호 설정
			$("cat_no").value = cat;
		},
		
		onFailure: function(){ 
			alert('Something went wrong...') 
		}   
	});

}

//카테고리 설정하기
function setCat<?=$j?>(txt){
	if(txt !=""){
		var opt = new Array();
		var opt2 = new Array();
		opt = txt.split("||");
		for(i=0; i<opt.length; i++){
			opt2 = opt[i].split("**");
			//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
			try{
				$("cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0]);
			}catch(e){}
		}
	}
}
<?
} //카테고리 js 끝
?>

</script>

<form name="frmInfo" method="post" action="product_evn.php" ENCTYPE="multipart/form-data" onSubmit="return CheckForm(this)">
<input type="hidden" name="evnMode" value="insert">


		<!-- 기본정보 -->
		<h3 class="admin-title-middle">기본정보</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>제품분류</th>
				<td class="space-left">
					<input type="hidden" name="cat_no">
					<select name="cat" onchange="getCat1(this.value);">
					<option value="">==========1차분류==========</option>
					<?for($i=0;$i<$arrCategory["total"];$i++){?>
					<option value="<?=$arrCategory["list"][$i][cat_no]?>"><?=$arrCategory["list"][$i][cat_name]?></option>
					<?}?>
					</select>

					<?
					for($i=2;$i<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$i++){ //카테고리 5차까지 만듬 => 1차 초기화는 따로위에서 함
					?>
					<select name="cat<?=$i?>" onchange="getCat<?=$i?>(this.value);">
					<option value="">==========<?=$i?>차분류==========</option>
					</select>
					<?
					}
					?>
				</td>
			</tr>
			<tr>
				<th>제품명</th>
				<td class="space-left"><input type="text" name="p_name" style="width:50%" maxlength="200" class="input" /></td>
			</tr>
			<tr>
				<th>사이즈</th>
				<td class="space-left"><input type="text" name="etc_1" style="width:50%" maxlength="200"class="input" /></td>
			</tr>
			<tr>
				<th>재질</th>
				<td class="space-left"><input type="text" name="etc_2" style="width:50%" maxlength="200"class="input" /></td>
			</tr>
			<tr>
				<th>간략설명</th>
				<td class="space-left"><input type="text" name="memo" style="width:50%" maxlength="255"class="input" /></td>
			</tr>
			<tr>
				<th>온라인 카타로그</th>
				<td class="space-left"><input type="file" name="catalog_file[]" style="width:50%" class="input" /> (제품설명서 등)</td>
			</tr>
		  </tbody>
		</table>

		<br />


		<!-- 제품 사진 -->
		<h3 class="admin-title-middle">제품 사진</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
		<?
		for($i=0;$i<$_SITE["PRODUCT"]["IMAGE_COUNT"];$i++){
		?>
		<tr>
		  <th>사진 <?=$i+1?></th>
		  <td class="space-left"><input type="file" name="photo_file[]" style="width:50%" class="input" /> <input type="radio" name="p_image" value="<?=$i?>" id="idPhoto<?=$i?>"<?=$i==0?" checked":""?>><label for="idPhoto<?=$i?>">대표이미지</label></td>
		</tr>
		<?
		}
		?>
		  <tbody>
		</table>


		<br>

		<!-- 제품 설명 -->
		<h3 class="admin-title-middle">제품 설명</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>설명</th>
				<td class="space-left">
					<?php
					$oFCKeditor = new FCKeditor('contents') ;
					$oFCKeditor->BasePath = '/common/fckeditor/' ;
					$oFCKeditor->Height = '400' ;
					$oFCKeditor->ToolbarSet = 'Mini';
					$oFCKeditor->Config['EnterMode'] = 'br';
					$oFCKeditor->Config['SkinPath'] = $oFCKeditor->BasePath . 'editor/skins/silver/' ;
					$oFCKeditor->Create() ;
					?>
				</td>
			</tr>
		  </tbody>
		</table>

		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="check"></span><input type="submit" value="제품 등록" style="font-weight:bold" /></span>
			</div>
		</div>	


</form>
  </div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>