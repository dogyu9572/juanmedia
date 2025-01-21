<?
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
?>
<script language="javascript">
function photoView(b_idx, idx) {
	var window_left = (screen.width-640)/2;
	var window_top = (screen.height-480)/2;
	obj = window.open('/module/category/photo_view.php?cat_no='+b_idx+'&idx='+idx,"photo_view_win",'width=620,height=400,status=no,scrollbars=no,top=' + window_top + ',left=' + window_left + '');
}
</script>
<script language="javascript">
function delBanner(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 배너를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}
</script>

<div class="container">
	<? include "./menu.php"; ?>
    <div id="admin-content">
		<div class="title">
			<?=$arrCategoryInfo["list"][0]['categoryname']?> <?=$_GET["mode"] == "modify"?"수정":"생성"?>
		</div>
		<div class="inbox write_tbl mo_break_write">
			<form name="frmInfo" method="post" action="category_evn.php" ENCTYPE="multipart/form-data" >
				<?php if($_GET["mode"] == "modify"){ ?>
				<input type="hidden" name="categoryid" value="<?=$arrCategoryInfo["list"][0]['categoryid']?>">
				<input type="hidden" name="evnMode" value="editCategory">
				<input type="hidden" name="cat_parent_no" value="<?=$arrInfo["list"][0]['cat_parent_no']?>">
				<input type="hidden" name="cat_no" value="<?=$arrInfo["list"][0]['cat_no']?>">
				<input type="hidden" name="cat_code" value="<?=$arrInfo["list"][0]['cat_code']?>">
				<?php }else{ ?>
				<input type="hidden" name="evnMode" value="writeCategory">
				<input type="hidden" name="s_categoryid" value="<?=$arrCategoryInfo["list"][0]['categoryid']?>">
				<input type="hidden" name="s_category" value="">
				<input type="hidden" name="s_cat_no" value="0">
				<input type="hidden" name="cat_code" value="<?=$arrInfo["list"][0]['cat_code']?>">
				<?php } ?>
			<table class="admin-table-type1">
			  <colgroup>
			  <col width="140" />
			  <col width="*" />
			  </colgroup>
			  <tbody>
				<tr>
				  <th>연구소명</th>
				  <td><div class="inputs"><input type="text" name="cat_name" value="<?=$arrInfo["list"][0]['cat_name']?>" style="width:200px;" class="input" /></div></td>
				</tr>
				<tr>
				  <th>영문명</th>
				  <td><div class="inputs"><input type="text" name="cat_name_eng" value="<?=$arrInfo["list"][0]['cat_name_eng']?>" style="width:200px;" class="input" /></div></td>
				</tr>
				<tr>
				  <th>내용</th>
				  <td>
					<textarea cols="100" rows="15" name="cat_content"><?=stripslashes($arrInfo["list"][0]['cat_content'])?></textarea>
				  </td>
				</tr>
				
				<tr>
				  <th>사용여부</th>
				  <td>
					<div class="inputs">
						<label class="radio"><label for="is_show_y"><input type="radio" id="is_show_y" name="cat_is_show" value="Y" <?=$arrInfo["list"][0]['cat_is_show']!="N"?" checked":""?>><i></i>Y</label>
						<label class="radio"><label for="is_show_n"><input type="radio" id="is_show_n" name="cat_is_show" value="N" <?=$arrInfo["list"][0]['cat_is_show']=="N"?" checked":""?>><i></i>N</label>
					</div>
				  </td>
				</tr>
				<? 
				//if($arrCate[0]=="1" && count($arrCate) == "3") {			
				if($arrInfo["list"][0]['cat_code']) {	
					if($arrInfo["total_catalog_files"]>0){
						for($i=0;$i<$arrInfo["total_catalog_files"];$i++){
							if(substr($arrInfo["catalog_files"][$i]['re_name'],0,2) == "m_") {
								$listimg1 = "Y";
								$num1 = $i;
							}
							if(substr($arrInfo["catalog_files"][$i]['re_name'],0,2) == "b_") {
								$listimg2 = "Y";
								$num2 = $i;
							}
						}
					}
				?>
				<tr style="display:none;">
				  <th>메뉴 이미지</th>
				  <td class="space-left">
					<?
					if($listimg1 == "Y"){
					?>
					<input type="checkbox" name="delCatalog[]" id="delCatalog" value="<?=$arrInfo["catalog_files"][$num1][idx]?>"><label for="delCatalog">삭제</label> &nbsp;<span style="color:#ccc">|</span>&nbsp; <a href="javascript:photoView('<?=$arrInfo["list"][0]['cat_no']?>','<?=$arrInfo["catalog_files"][$num1][idx]?>');"><?=$arrInfo["catalog_files"][$num1][ori_name]?></a>
					<?
					}else{
					?>
					<input type="file" name="catalog_file[]" style="width:30%" class="input" />
					<input type="hidden" name="memo_name[]" value="m">
					<?}?>
				  </td>
				</tr>
				<? } ?>
			  </tbody>
			</table>
			<div class="btns">
				<a href="category.php?categoryid=<?=$arrCategoryInfo["list"][0]['categoryid']?>&cat_no=<?=$currCatno?>" class="btn btn_list">목록보기</a>
				<a href="javascript:void(0)" onclick="location.reload()" class="btn btn_cancel">취소</a>
				<button class="btn btn_save" type="submit">저장</button>
			</div>

		</form>
	</div>
<form name="frmListHidden" method="post" action="category_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="cat_no" value="<?=$arrInfo["list"][0]['cat_no']?>">
</form>

	</div>
</div>