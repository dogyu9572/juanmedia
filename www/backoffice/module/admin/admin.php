<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if(!in_array("admin_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale="20";
$offset = "0";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getArticleList($_conf_tbl["admin"], $scale, postNullCheck('offset'),"");

//_DEBUG($arrMenuList);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function delAdmin(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 관리자를 삭제 하시겠습니까?");
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
</script>
<div class="container">

	<div class="title">관리자 목록</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>
			<div class="btns flx">
				<form name="frmBBS" method="post" action="admin_evn.php">
				<input type="hidden" name="evnMode" value="createAdmin">				
				<div>
					<label for="id">신규 관리자 ID :</label>
					<input type="text" name="id" id="id" maxlength="20" style="width:160px;" class="input"/>					
				</div>
				</form>	
				<a href="javascript:void(0);" class="btn btns" onclick="CheckForm(document.frmBBS)">신규등록</a>
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
						<col class="w10p">
						<col class="*">
						<col class="w10p">
						<col class="w10p">
						<col class="w8p">
						<col class="w10p">
						<col class="w12p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">관리자ID</th>
							<th class="pc_vw">이름</th>
							<th class="pc_vw">설명</th>
							<th class="pc_vw">연락처</th>
							<th class="pc_vw">이메일</th>
							<th class="pc_vw">권한</th>
							<th class="pc_vw">관리권한</th>
							<th class="pc_vw">생성일</th>
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
							<td><i class="mo_vw"><a href="admin_info.php?idx=<?=$arrList['list'][$i]['idx']?>" class="linktxt">관리자ID</i><?=$arrList['list'][$i]['a_id']?></a></td>

							<td><i class="mo_vw">이름</i><?=$arrList['list'][$i]['a_name']?></td>
							<td><i class="mo_vw">설명</i><?=$arrList['list'][$i]['a_note']?></td>
							<td><i class="mo_vw">연락처</i><?=$arrList['list'][$i]['a_phone']?></td>
							<td><i class="mo_vw">이메일</i><?=$arrList['list'][$i]['a_email']?></td>
							<td><i class="mo_vw">권한</i><?=$arrList['list'][$i]['a_grade']?></td>
							<td><i class="mo_vw">관리권한</i>								
							<?
							$arrAuthCode = explode(",",$arrList['list'][$i]['a_auth']);
							foreach($arrAuthCode as $val){
								if(isset($arrayMenuList[trim($val)])){
									echo "<div>- " . $arrayMenuList[trim($val)] . "</div>";
								}
							}
							?>								
							</td>
							<td><i class="mo_vw">생성일</i><?=$arrList['list'][$i]['a_id']=="homepage"?"":$arrList['list'][$i]['a_date']?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns"><?if($arrList['list'][$i]['a_id']!="homepage"){?>
									<a href="admin_info.php?idx=<?=$arrList['list'][$i]['idx']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delAdmin('<?=$arrList['list'][$i]['idx']?>');">삭제</button><?}?>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="10" >생성된 관리자가 없습니다.</td>
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
 			<!-- <div class="btns">
 							<form name="frmBBS" method="post" action="admin_evn.php">
 							<input type="hidden" name="evnMode" value="createAdmin">				
 							<div>
 								<label for="id">신규 관리자 ID :</label>
 								<input type="text" name="id" id="id" maxlength="20" style="width:160px;" class="input"/>					
 							</div>
 							</form>	
 							<a href="javascript:void(0);" class="btn" onclick="CheckForm(document.frmBBS)">신규등록</a>
 						</div> -->
		</div>
	</div>

</div>

<form name="frmBBSHidden" method="post" action="admin_evn.php">
<input type="hidden" name="evnMode" value="deleteAdmin">
<input type="hidden" name="idx">
</form>	

<?php include("pub/inc/footer.php") ?>