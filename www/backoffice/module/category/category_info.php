<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

$arrPath = getCategoryPath(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));		## 상단 링크

#######################  목록 보기
$arrCate = explode("/", $arrInfo["list"][0]['cat_code']);
if($arrCate[4]) {
	$currCatno = $arrCate[3];
} else if($arrCate[3]) {
	$currCatno = $arrCate[2];
} else if($arrCate[2]) {
	$currCatno = $arrCate[1];
} else if($arrCate[1]) {
	$currCatno = $arrCate[0];
} else if($arrCate[0]) {
	$currCatno = "";
}

if($arrInfo["total_catalog_files"]>0){
	for($i=0;$i<$arrInfo["total_catalog_files"];$i++){
		if(substr($arrInfo["catalog_files"][$i]['re_name'],0,3) == "c1_") {
			$arrInfo["catalog_files"][0]["file_c1_name"]	= $arrInfo["catalog_files"][$i]['ori_name'];
			$arrInfo["catalog_files"][0]["file_c1_url"]		= "/uploaded/category/".$arrInfo["catalog_files"][$i]['re_name'];
			$arrInfo["catalog_files"][0]["file_c1_idx"]		= $arrInfo["catalog_files"][$i]['idx'];
		}
		if(substr($arrInfo["catalog_files"][$i]['re_name'],0,3) == "c2_") {
			$arrInfo["catalog_files"][0]["file_c2_name"]	= $arrInfo["catalog_files"][$i]['ori_name'];
			$arrInfo["catalog_files"][0]["file_c2_url"]		= "/uploaded/category/".$arrInfo["catalog_files"][$i]['re_name'];
			$arrInfo["catalog_files"][0]["file_c2_idx"]		= $arrInfo["catalog_files"][$i]['idx'];
		}
	}
}

//DB해제
SetDisConn($dblink);
?>
<script type="text/javascript">
<!--

function frmCheck(frm){
	if(!frm.cat_name.value){
		alert('카테고리명을 입력하세요.');
		frm.cat_name.focus();
		return;
	}
	//alert($("#cat_val").val())
	if($("#cat_val").val()=="undefined" || $("#cat_val").val()==null ){
		//
	}else{
		if(!frm.cat_value.value){
			alert('상품코드를 입력하세요.');
			frm.cat_value.focus();
			return;
		}
	}
	frm.submit();
}

function fnSave(){
	var frm = document.frmInfo;
	frm.rt_url.value = '<?=$_SERVER['REQUEST_URI']?>';
	frm.altYN.value = 'Y';
	frm.submit();
}	
//-->
</script>
<div class="container">

	<div class="title">분류 수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmInfo" method="post" action="category_evn.php" ENCTYPE="multipart/form-data" >
			<input type="hidden" name="evnMode" value="editCategory">
			<input type="hidden" name="cat_no" value="<?=$arrInfo["list"][0]['cat_no']?>">
			<input type="hidden" name="cat_code" value="<?=$arrInfo["list"][0]['cat_code']?>">
			<input type="hidden" name="rt_url" value="/backoffice/module/category/category.php?rlist=T">
			<input type="hidden" name="altYN" value="N">

			<div class="tit">
				<!--<a href="category.php?cat_no=<?=$arrPath['list'][0]['cat_no']?>">ROOT</a>-->
				<?
				$comma = "";
				if($arrPath['total'] > 0){
					for($i=0; $i < $arrPath['total']; $i++){
				?>
				<?=$comma?> <a href="category.php?cat_no=<?=$arrPath['list'][$i]['cat_no']?>"><?=$arrPath['list'][$i]['cat_name']?></a>
				<?
						$comma = "&gt;";
					}
				}
				?>
			<?//=$arrInfo["list"][0]['cat_name']?> 수정 <i>*</i></div>
			
			<table>
				<tr>
					<th><?=$arrPath['list'][0]['cat_name']?> 명</th>
					<td><div class="inputs"><input type="text" class="w4" name="cat_name" maxlength="30" value="<?=stripslashes($arrInfo["list"][0]['cat_name'])?>"><em style="color:red;">&nbsp;<?=$arrCate[0]=="3"?"":""?></em></div></td>
				</tr>
				<?
				if($arrCate[0]=="2"){
					if($arrInfo["list"][0]['cat_depth']=="1"){				
				?>
				<tr>
					<th>타이틀</th>
					<td><div class="inputs"><input type="text" class="w4" name="cat_engname" maxlength="50" value="<?=stripslashes($arrInfo["list"][0]['cat_engname'])?>"></div></td>
				</tr>
				<tr>
					<th>설명글</th>
					<td><div class="inputs"><textarea name="cat_content" style="width:70%;heigth:100px;padding:10px;"><?=stripslashes($arrInfo["list"][0]['cat_content'])?></textarea></div></td>
				</tr>
				<tr>
					<th>이미지파일</th>
					<td>
						<div class="inputs">
						<?if($arrInfo["catalog_files"][0]["file_c1_idx"]){?>
						<label class="check"><input type="checkbox" name="delCatalog[]" value="<?=$arrInfo["catalog_files"][0]["file_c1_idx"]?>"><i></i>삭제</label>
						<em>file : <a href="<?=$arrInfo["catalog_files"][0]["file_c1_url"]?>" download="<?=$arrInfo["catalog_files"][0]["file_c1_name"]?>"><?=$arrInfo["catalog_files"][0]["file_c1_name"]?></a></em>
						<?}else{?>
							<div class="filebutton">
								<span>파일 선택</span>							
								<input name="catalog_file[]" type="file" class="searchfile" title="파일 찾기"><input type="hidden" name="memo_name[]" value="c1">							
							</div>
							<div class="filebox">선택된 파일 없음</div>
						<?}?>
						</div>
					</td>
				</tr>
				<?
					}
				}
				?>

				<tr>
					<th>사용</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="cat_is_show" value="Y"<?=$arrInfo["list"][0]['cat_is_show']=="Y"?" checked":""?>><i></i>Y</label>
						<label class="radio"><input type="radio" name="cat_is_show" value="N"<?=$arrInfo["list"][0]['cat_is_show']=="N"?" checked":""?>><i></i>N</label> 						
					</div></td>
				</tr>	
				<?
				if($arrCate[0]=="2"){
					if($arrInfo["list"][0]['cat_depth']=="1"){				
				?>
				
				<?
					}else if($arrInfo["list"][0]['cat_depth']=="2"){
				?>				
				
				<?
					}
				}
				?>
			</table>
		
			<div class="btns">
				<a href="category.php?cat_no=<?=$currCatno?>" class="btn btn_list">목록보기</a>
				<button class="btn btn_save" type="button" onclick="frmCheck(document.frmInfo)">저장</button>
			</div>
		</form>
	</div> <!-- //inbox -->
</div>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//달력
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
// 선택/해지
	var $allCheck = $('#allCheck');
	$allCheck.change(function () {
		var $this = $(this);
		var checked = $this.prop('checked');
		$('.chk_list').prop('checked', checked);
	});
	var boxes = $('.chk_list');
	boxes.change(function () {
		var boxLength = boxes.length;
		var checkedLength = $('.chk_list:checked').length;
		var selectallCheck = (boxLength == checkedLength);
		$allCheck.prop('checked', selectallCheck);
	});
});
//]]>
</script>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>