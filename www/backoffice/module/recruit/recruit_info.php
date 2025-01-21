<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/recruit/recruit.lib.php";

if(!in_array("recruit_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$urIdx = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['uridx']);

$arrInfo = getRecruit01Info($urIdx);
$addInfo = getRecruitAddList($urIdx,"EDU");
$careerInfo = getRecruitCareerList($urIdx);
$addInfo01 = getRecruitAddList($urIdx,"LNG");
$addInfo02 = getRecruitAddList($urIdx,"AWD");
$addInfo03 = getRecruitAddList($urIdx,"LIC");

//DB해제

SetDisConn($dblink);

?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">인재DB</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 인재DB</div>
	</div>

<script language="javascript">
</script>

		<!-- 기본정보 -->
		<h3 class="admin-title-middle">기본정보</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="20%" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>이름</th>
				<td class="space-left"><?=$arrInfo["list"][0]['ur_name']?></td>
			</tr>
			<tr>
				<th>성별</th>
				<td class="space-left"><?=$arrInfo["list"][0]['ur_gen']?></td>
			</tr>
			<tr>
				<th>생년월일</th>
				<td class="space-left"><?=AES_decrypt($arrInfo['list'][0]['ur_birth'])?></td>
			</tr>
			<tr>
				<th>국적</th>
				<td class="space-left"><? if($arrInfo['list'][0]['ur_nat']=="Y"){ echo "내국인"; }else{ echo "외국인"; } ?></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td class="space-left"><?=AES_decrypt($arrInfo['list'][0]['ur_tel'])?></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td class="space-left"><?=AES_decrypt($arrInfo['list'][0]['ur_email'])?></td>
			</tr>
			<tr>
				<th>주소</th>
				<td class="space-left">(<?=$arrInfo['list'][0]['ur_zip']?>) <?=$arrInfo['list'][0]['ur_zip']?> <?=AES_decrypt($arrInfo['list'][0]['ur_addr2'])?></td>
			</tr>
			<tr>
				<th>보훈여부</th>
				<td class="space-left"><? if($arrInfo['list'][0]['ur_veteran']=="N"){ echo "비대상"; }else{ echo "대상"; } ?></td>
			</tr>
			<tr>
				<th>장애여부</th>
				<td class="space-left"><? if($arrInfo['list'][0]['ur_handi']=="N"){ echo "비대상"; }else{ echo "대상"; } ?></td>
			</tr>
			<tr>
				<th>병역여부</th>
				<td class="space-left">
				<?
					if($arrInfo['list'][0]['ur_milit']=="A"){
						echo "필";
					}elseif($arrInfo['list'][0]['ur_milit']=="B"){
						echo "미필";
					}elseif($arrInfo['list'][0]['ur_milit']=="C"){
						echo "면제";
					}elseif($arrInfo['list'][0]['ur_milit']=="D"){
						echo "비대상(여성,외국인)";
					}
				?>
				</td>
			</tr>
			<tr>
				<th>병역구분</th>
				<td class="space-left">
				<?
					if($arrInfo['list'][0]['ur_milit_gb']=="AA"){
						echo "병역필_현역(병)";
					}elseif($arrInfo['list'][0]['ur_milit_gb']=="AB"){
						echo "병역필_현역(장교)";
					}elseif($arrInfo['list'][0]['ur_milit_gb']=="AC"){
						echo "병역필_현역(부사관)";
					}elseif($arrInfo['list'][0]['ur_milit_gb']=="AD"){
						echo "병역필_공익근무요원";
					}elseif($arrInfo['list'][0]['ur_milit_gb']=="AE"){
						echo "병역필_산업기능요원";
					}elseif($arrInfo['list'][0]['ur_milit_gb']=="AF"){
						echo "병역필_전문연구요원";
					}elseif($arrInfo['list'][0]['ur_milit_gb']=="AG"){
						echo "복무중(전직가능)_전문연구요원";
					}
				?>
				</td>
			</tr>
			<tr>
				<th>복무기간</th>
				<td class="space-left"><?=$arrInfo['list'][0]['ur_milit_sdt']?> ~ <?=$arrInfo['list'][0]['ur_milit_edt']?></td>
			</tr>
		  </tbody>
		</table>
		<br/>

		<h3 class="admin-title-middle">고등학교</h3>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="20%">학교명</th>
					<th width="*">재학기간</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?=$arrInfo['list'][0]['ur_high']?></td>
					<td><?=$arrInfo['list'][0]['ur_high_sdt']?> ~ <?=$arrInfo['list'][0]['ur_high_edt']?></td>
				</tr>
			</tbody>
		</table>
		<br/>
		
		<h3 class="admin-title-middle">대학교</h3>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="20%">학교명</th>
					<th width="20%">전공</th>
					<th width="15%">부전공</th>
					<th width="*">재학기간</th>
					<th width="15%">학점ㆍ만점</th>
				</tr>
			</thead>
			<tbody>
			<?if($addInfo['list']['total'] > 0):?>
				<?for ($i=0;$i<$addInfo['list']['total'];$i++) {?>
				<tr>
					<td><?=$addInfo['list'][$i]['ad_name']?></td>
					<td><?=$addInfo['list'][$i]['ad_ext1']?></td>
					<td><?=$addInfo['list'][$i]['ad_ext2']?></td>
					<td><?=$addInfo['list'][$i]['ad_sdt']?> ~ <?=$addInfo['list'][$i]['ad_edt']?></td>
					<td><?=$addInfo['list'][$i]['ad_ext3']?></td>
				</tr>
				<?}?>
			<?else:?>
				<tr height="100">
					<td width="100%" colspan="5">해당 정보가 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<br/>
		
		<h3 class="admin-title-middle">경력사항</h3>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="20%">회사명</th>
					<th width="20%">부서명/직무</th>
					<th width="15%">직급</th>
					<th width="15%">연봉(만원)</th>
					<th width="*">근무기간</th>
					<th width="10%">재직여부</th>
				</tr>
			</thead>
			<tbody>
			<?if($careerInfo['list']['total'] > 0):?>
				<?for ($i=0;$i<$careerInfo['list']['total'];$i++) {?>
				<tr>
					<td rowspan="3"><?=$careerInfo['list'][$i]['cr_name']?></td>
					<td><?=$careerInfo['list'][$i]['cr_dept']?>/<?=$careerInfo['list'][$i]['cr_job']?></td>
					<td><?=$careerInfo['list'][$i]['cr_pos']?></td>
					<td><?=$careerInfo['list'][$i]['cr_salary']?>만원</td>
					<td><?=$careerInfo['list'][$i]['cr_sdt']?> ~ <?=$careerInfo['list'][$i]['cr_edt']?></td>
					<td><? if($addInfo['list'][$i]['cr_yn']=='Y'){ echo '재직'; } ?></td>
				</tr>
				<tr>
					<th>담당업무 상세</th>
					<td colspan="4"><?=stripslashes($careerInfo["list"][$i]['cr_etc1'])?></td>
				</tr>
				<tr>
					<th>주요 성과/역할 상세</th>
					<td colspan="4"><?=stripslashes($careerInfo["list"][$i]['cr_etc2'])?></td>
				</tr>
				<?}?>
			<?else:?>
				<tr height="100">
					<td width="100%" colspan="6">해당 정보가 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<br/>
		
		<h3 class="admin-title-middle">외국어사항</h3>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="20%">언어</th>
					<th width="20%">시험</th>
					<th width="15%">점수/등급</th>
					<th width="*">취득일자</th>
					<th width="15%">발급기관</th>
				</tr>
			</thead>
			<tbody>
			<?if($addInfo01['list']['total'] > 0):?>
				<?for ($i=0;$i<$addInfo01['list']['total'];$i++) {?>
				<tr>
					<td><?=$addInfo01['list'][$i]['ad_ext1']?></td>
					<td><?=$addInfo01['list'][$i]['ad_name']?></td>
					<td><?=$addInfo01['list'][$i]['ad_ext2']?></td>
					<td><?=$addInfo01['list'][$i]['ad_sdt']?></td>
					<td><?=$addInfo01['list'][$i]['ad_ext3']?></td>
				</tr>
				<?}?>
			<?else:?>
				<tr height="100">
					<td width="100%" colspan="5">해당 정보가 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<br/>
		
		<h3 class="admin-title-middle">수상경력</h3>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="20%">수상명</th>
					<th width="20%">수여기관</th>
					<th width="*">수여일시</th>
					<th width="15%">발급기관</th>
				</tr>
			</thead>
			<tbody>
			<?if($addInfo02['list']['total'] > 0):?>
				<?for ($i=0;$i<$addInfo02['list']['total'];$i++) {?>
				<tr>
					<td><?=$addInfo02['list'][$i]['ad_name']?></td>
					<td><?=$addInfo02['list'][$i]['ad_ext1']?></td>
					<td><?=$addInfo02['list'][$i]['ad_sdt']?></td>
					<td><?=$addInfo02['list'][$i]['ad_ext2']?></td>
				</tr>
				<?}?>
			<?else:?>
				<tr height="100">
					<td width="100%" colspan="4">해당 정보가 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<br/>
		
		<h3 class="admin-title-middle">자격증</h3>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="20%">자격증명</th>
					<th width="20%">등록번호</th>
					<th width="*">취득일자</th>
					<th width="15%">발급기관</th>
				</tr>
			</thead>
			<tbody>
			<?if($addInfo03['list']['total'] > 0):?>
				<?for ($i=0;$i<$addInfo03['list']['total'];$i++) {?>
				<tr>
					<td><?=$addInfo03['list'][$i]['ad_name']?></td>
					<td><?=$addInfo03['list'][$i]['ad_ext1']?></td>
					<td><?=$addInfo03['list'][$i]['ad_sdt']?></td>
					<td><?=$addInfo03['list'][$i]['ad_ext2']?></td>
				</tr>
				<?}?>
			<?else:?>
				<tr height="100">
					<td width="100%" colspan="4">해당 정보가 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<br/>
		
		<!-- 자기소개서 -->
		<h3 class="admin-title-middle">자기소개서</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="20%" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>자기소개서1</th>
				<td class="space-left" style="min-height:80px;"><?=stripslashes($arrInfo["list"][0]['ur_etc1'])?></td>
			</tr>
			<tr>
				<th>자기소개서2</th>
				<td class="space-left" style="min-height:80px;"><?=stripslashes($arrInfo["list"][0]['ur_etc2'])?></td>
			</tr>
			<tr>
				<th>자기소개서3</th>
				<td class="space-left" style="min-height:80px;"><?=stripslashes($arrInfo["list"][0]['ur_etc3'])?></td>
			</tr>
			<tr>
				<th>자기소개서4</th>
				<td class="space-left" style="min-height:80px;"><?=stripslashes($arrInfo["list"][0]['ur_etc4'])?></td>
			</tr>
		  </tbody>
		</table>
		<br/>

		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge"><input type="button" value="목록으로" style="font-weight:bold" onclick="javascript:history.back();" /></span>
			</div>
		</div>	

	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
