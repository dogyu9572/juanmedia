<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["member_level"], $_REQUEST[idx]);
//_DEBUG($arrInfo);

$arrList = getCouponListAdmin(0, 0, "Y");

//DB해제
SetDisConn($dblink);

?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">회원등급 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 회원등급 수정</div>
	</div>

<script language="javascript">
function checkForm(frm){
	if (frm.level_no.value==""){
		alert("회원등급을 입력해 주세요.");
		frm.level_no.focus();
		return false;
	}
	if (frm.level_name.value==""){
		alert("회원등급 이름을 입력해 주세요.");
		frm.level_name.focus();
		return false;
	}
}
</script>
<form name="frmBBS" method="post" action="member_level_evn.php" onSubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="editMemberLevel">
<input type="hidden" name="idx" value="<?=$_REQUEST[idx]?>">

	<table class="admin-table-type1">
	  <colgroup>
	  <col width="140" />
	  <col width="*" />
	  </colgroup>
	  <tbody>
		<tr>
			<th>회원등급</th>
			<td class="space-left"><input type="text" name="level_no" value="<?=$arrInfo["list"][0]['level_no']?>" style="width:200px;" class="input" /></td>
		</tr>
		<tr>
			<th>회원등급 이름</th>
			<td class="space-left"><input type="text" name="level_name" value="<?=$arrInfo["list"][0]['level_name']?>" style="width:200px;" class="input" /></td>
		</tr>
		<tr>
			<th>구입 적립금</th>
			<td class="space-left"><input type="text" name="level_point" value="<?=$arrInfo["list"][0][level_point]?>" style="width:100px;" class="input" />% (숫자만 입력하세요)</td>
		</tr>
		<tr>
			<th>등급 기준금액</th>
			<td class="space-left"><input type="text" name="level_price" value="<?=$arrInfo["list"][0][level_price]?>" style="width:100px;" class="input" />원 (숫자만 입력하세요)</td>
		</tr>
		<tr>
			<th>쿠폰1</th>
			<td class="space-left">
				<select name="coupon1">
					<option value="">선택</option>
					<?if($arrList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
					<option value="<?=$arrList['list'][$i]['idx']?>"<?=$arrList['list'][$i]['idx']==$arrInfo["list"][0][coupon1]?" selected":""?>><?=stripslashes($arrList['list'][$i]['coupon_name'])?></option>
					<?}endif;?>
				</select>	
			</td>
		</tr>
		<tr>
			<th>쿠폰2</th>
			<td class="space-left">
				<select name="coupon2">
					<option value="">선택</option>
					<?if($arrList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
					<option value="<?=$arrList['list'][$i]['idx']?>"<?=$arrList['list'][$i]['idx']==$arrInfo["list"][0][coupon2]?" selected":""?>><?=stripslashes($arrList['list'][$i]['coupon_name'])?></option>
					<?}endif;?>
				</select>	
			</td>
		</tr>
		<tr>
			<th>승급헤택1</th>
			<td class="space-left">
				<select name="favor1">
					<option value="">선택</option>
					<?if($arrList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
					<option value="<?=$arrList['list'][$i]['idx']?>"<?=$arrList['list'][$i]['idx']==$arrInfo["list"][0][favor1]?" selected":""?>><?=stripslashes($arrList['list'][$i]['coupon_name'])?></option>
					<?}endif;?>
				</select>
				/
				수량: <select name="favor1_ea">
							<option value="1"<?=$arrInfo["list"][0][favor1_ea]=="1"?" selected":""?>>1</option>
							<option value="2"<?=$arrInfo["list"][0][favor1_ea]=="2"?" selected":""?>>2</option>
							<option value="3"<?=$arrInfo["list"][0][favor1_ea]=="3"?" selected":""?>>3</option>
							<option value="4"<?=$arrInfo["list"][0][favor1_ea]=="4"?" selected":""?>>4</option>
							<option value="5"<?=$arrInfo["list"][0][favor1_ea]=="5"?" selected":""?>>5</option>
					</select>
			</td>
		</tr>
		<tr>
			<th>승급헤택2</th>
			<td class="space-left">
				<select name="favor2">
					<option value="">선택</option>
					<?if($arrList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
					<option value="<?=$arrList['list'][$i]['idx']?>"<?=$arrList['list'][$i]['idx']==$arrInfo["list"][0][favor2]?" selected":""?>><?=stripslashes($arrList['list'][$i]['coupon_name'])?></option>
					<?}endif;?>
				</select>	
			</td>
		</tr>
		<tr>
			<th>생성일</th>
			<td class="space-left"><?=$arrInfo["list"][0]['wdate']?></td>
		</tr>
	  </tbody>
	</table>

	<div class="admin-buttons">
		<div class="cen">
			<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="정보수정" style="font-weight:bold" /></span>
		</div>
	</div>	
</form>
</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>