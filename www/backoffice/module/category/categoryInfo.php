<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("category_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale="20";
$offset = "0";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getArticleList("tbl_category_info", $scale, postNullCheck('offset'), "");
//_DEBUG($arrList);
$arrLevel = getArticleList($_conf_tbl["member_level"], 0, 0, "order by level_no desc ");

for($i=0;$i<$arrLevel["total"];$i++){
	$arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}
//DB해제
SetDisConn($dblink);

if(!isset($arrList['list']['total'])){
	$arrList['list']['total'] = 0;
}

?>
<script language="javascript">
function delBoard(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 게시판을 삭제 하시겠습니까?\n\n관련 데이터들도 모두 삭제되며 복구되지 않습니다.");
	if(cfm==true){
		document.frmBBSHidden.idx.value = idx;
		document.frmBBSHidden.submit();
	}
}

function CheckForm(frm){ 
	if (frm.id.value==""){
		alert("ID 를 입력하여 주십시요.");
		frm.id.focus();
		return false;
	}
	if (frm.id.value.length < 2 || frm.id.value.length > 20) {
		alert("ID는 2~20자리입니다.");
		frm.id.focus();
		return false;
	}
	if (hangul_chk(frm.id.value) != true ){
		alert("ID에 한글이나 여백은 사용할 수 없습니다.");
		frm.id.focus();
		return false;
	}
	frm.submit();
}
function CheckForder(frm){ 
	if (frm.nfName.value==""){
		alert("스킨명을 입력하여 주십시요.");
		frm.nfName.focus();
		return false;
	}
	if (frm.nfName.value.length < 2 || frm.nfName.value.length > 20) {
		alert("스킨명은 2~20자리입니다.");
		frm.nfName.focus();
		return false;
	}
	if (hangul_chk(frm.nfName.value) != true ){
		alert("스킨명에 한글이나 여백은 사용할 수 없습니다.");
		frm.nfName.focus();
		return false;
	}
	frm.submit();
}
</script>
<div class="container">

	<div class="title">카테고리 목록<?=in_array("service_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"])?></div>

	<div class="inbox">
		
		<div class="bdr_btm">
			<div class="btns">
				<form name="frmForder" method="post" action="category_evn.php">
				<input type="hidden" name="evnMode" value="createForder">				
				<div>					
					<select name="f_skin">
					<?
					$dirhandle = opendir($_SITE["CATEGORY_PATH"]."/skin");
					while($filename = readdir($dirhandle)){
					  if($filename == '.' || $filename == '..'){
					  }else{
						  if($filename=="default"){
							echo "<option value='$filename' selected>　　$filename</option>\n";
						  }else{
							echo "<option value='$filename'>$filename</option>\n";
						  }
					  }
					}
					?></select>
					<label for="nfName">&nbsp;▶&nbsp;&nbsp;신규 스킨명 :</label>
					<input type="text" name="nfName" id="nfName" maxlength="20" style="width:160px;" class="input"/>
				</div>
				</form>	
				<a href="javascript:void(0);" class="btn" onclick="CheckForder(document.frmForder)">스킨생성</a>
			</div>
		</div>

		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['list']['total'])?></strong></div>				
			</div>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w10p">
						<col class="*">
						<col class="w10p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">카테고리ID</th>
							<th class="pc_vw">카테고리명</th>
							<th class="pc_vw">스킨</th>
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=number_format($arrList['total']-$i-$offset)?></td>
							<td><i class="mo_vw">카테고리ID</i><?=$arrList['list'][$i]['categoryid']?></td>
							<td><i class="mo_vw">카테고리명</i><a href="category.php?categoryid=<?=$arrList['list'][$i]['categoryid']?>"> <?=$arrList['list'][$i]['categoryname']?></a></td>	
							<td><i class="mo_vw">스킨</i><?=$arrList['list'][$i]['skin']?></td>							
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="categoryInfo_form.php?idx=<?=$arrList['list'][$i]['idx']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delBoard('<?=$arrList['list'][$i]['idx']?>');">삭제</button>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="5" >생성된 카테고리가 없습니다.</td>
					</tr>
					<?}?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="bdr_btm">
			<div class="paging">			
			<?=pageNavigationBackoffice($arrList['total'],$scale,$pagescale,$offset,"")?>
			</div>
			<div class="btns">
				<form name="frmBBS" method="post" action="category_evn.php">
				<input type="hidden" name="evnMode" value="createCategoryInfo">		
				<div>
					<label for="id">신규 카테고리 ID :</label>
					<input type="text" name="id" id="id" maxlength="20" style="width:160px;" class="input"/>
				</div>
				</form>	
				<a href="javascript:void(0);" class="btn" onclick="CheckForm(document.frmBBS)">신규등록</a>
			</div>
		</div>		
	</div>
</div>

<form name="frmBBSHidden" method="post" action="category_evn.php">
<input type="hidden" name="evnMode" value="deleteCategoryInfo">
<input type="hidden" name="idx">
</form>
<?php include("pub/inc/footer.php") ?>