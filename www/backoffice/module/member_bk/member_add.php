<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));
$arrLevel = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");

//DB해제
SetDisConn($dblink);

$todate = date("YmdHis");	// 현재일
$user_id = "member_".sha1($todate);
//$user_id = "member_".$todate;
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">회원 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 회원등록</div>
	</div>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="javascript">
function checkForm(frm){
	if(frm.user_pw.value.length > 0){
		if (frm.user_pw.value==""){
			alert("비밀번호를 입력해 주세요.");
			frm.user_pw.focus();
			return false;
		}
		if (frm.user_pw2.value==""){
			alert("비밀번호 확인을 입력해 주세요.");
			frm.user_pw2.focus();
			return false;
		}
		if (frm.user_pw.value != frm.user_pw2.value){
			alert("비밀번호가 일치하지 않습니다.");
			frm.user_pw2.focus();
			return false;
		}
	}
	if (frm.user_name.value.length < 2){
		alert("이름을 입력해 주세요.");
		frm.user_name.focus();
		return false;
	}
	if (frm.email_id.value.length < 2){
		alert("이메일을 입력해 주세요.");
		frm.email_id.focus();
		return false;
	}

	if (frm.mobile_1.value.length < 1){
		alert("휴대번호를 입력해 주세요.");
		frm.mobile_1.focus();
		return false;
	}
	if (frm.mobile_2.value.length < 1){
		alert("휴대번호를 입력해 주세요.");
		frm.mobile_2.focus();
		return false;
	}
	if (frm.mobile_3.value.length < 1){
		alert("휴대번호를 입력해 주세요.");
		frm.mobile_3.focus();
		return false;
	}

	/*
	if (frm.zip.value.length < 2){
		alert("우편번호를 입력해 주세요.");
		frm.zip.focus();
		return false;
	}
	if (frm.address.value.length < 2){
		alert("주소를 입력해 주세요.");
		frm.address.focus();
		return false;
	}
	if (frm.address_ext.value.length < 2){
		alert("상세주소를 입력해 주세요.");
		frm.address_ext.focus();
		return false;
	}
	*/

	
}

function inNumber(str){
	// 숫자만 입력
	str.value = str.value.replace(/[^0-9]/g,"");	
}
function zipSearch(tp){
	var obj = window.open('/module/zipcode/zipcode.php?tp='+tp,'주소찾기','width=463, height=305, scrollbars=1');
	obj.focus();
}
</script>


<form name="memberForm" method="post" action="member_evn.php" onsubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="insert">
<input type="hidden" name="rt_url" value="<?=$_REQUEST['listURL']?>">

<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<tr>
		<th>회원등급</th>
		<td class="space-left">
		<select name="user_level">
		<option value="">등급선택</option>
		<?for ($i=0;$i<$arrLevel['total'];$i++) {?>
		<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]["user_level"]?" selected":""?> selected><?=$arrLevel['list'][$i]['level_name']?></option>
		<?}?>
		</select>
		</td>
	</tr>
	<tr>
		<th>아이디</th>
		<td class="space-left"><input name="user_id" type="text" class="input" size="50" value="<?=$user_id?>" class="input" size="50" style="width:90%"></td>
	</tr>
	<tr>
		<th>비밀번호</th>
		<td class="space-left"><input name="user_pw" type="password" class="input" size="20" value="12345678"> 기본세팅값 ) 12345678</td>
	</tr>
	<tr>
		<th>비밀번호 확인</th>
		<td class="space-left"><input name="user_pw2" type="password" class="input" size="20" value="12345678"></td>
	</tr>
	<tr>
		<th>이 름</th>
		<td class="space-left"><input name="user_name" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td class="space-left"><input name="email_id" type="text" class="input" size="20">
			<select name='email_domain' id='email_domain' title='이메일 도메인 선택박스' >
				<option value=''>직접입력</option>
				<option value='naver.com' >naver.com</option>
				<option value='chol.com' >chol.com</option>
				<option value='daum.net' >daum.net</option>
				<option value='dreamwiz.com' >dreamwiz.com</option>
				<option value='empal.com' >empal.com</option>
				<option value='freechal.com' >freechal.com</option>
				<option value='gmail.com' >gmail.com</option>
				<option value='hanafos.com' >hanafos.com</option>
				<option value='hanmail.net' >hanmail.net</option>
				<option value='hanmir.com' >hanmir.com</option>
				<option value='hitel.net' >hitel.net</option>
				<option value='hotmail.com' >hotmail.com</option>
				<option value='korea.com' >korea.com</option>
				<option value='lycos.co.kr' >lycos.co.kr</option>
				<option value='nate.com' >nate.com</option>
				<option value='netian.com' >netian.com</option>
				<option value='paran.com' >paran.com</option>
				<option value='yahoo.com' >yahoo.com</option>
				<option value='yahoo.co.kr' >yahoo.co.kr</option>
			</select></td>
	</tr>
	<!--tr>
		<th>전화번호</th>
		<td class="space-left"><input name="phone_1" type="text" class="input" style="width:40px" value="<?=$arrPhone[0]?>" maxlength="4">
			-
			<input name="phone_2" type="text" class="input" style="width:40px" value="<?=$arrPhone[1]?>" maxlength="4">
			-
			<input name="phone_3" type="text" class="input" style="width:40px" value="<?=$arrPhone[2]?>" maxlength="4"></td>
	</tr-->
	<tr>
		<th>휴대번호</th>
		<td class="space-left"><input name="mobile_1" type="text" class="input" style="width:40px" value="<?=$arrMobile[0]?>" maxlength="4">
			-
			<input name="mobile_2" type="text" class="input" style="width:40px" value="<?=$arrMobile[1]?>" maxlength="4">
			-
			<input name="mobile_3" type="text" class="input" style="width:40px" value="<?=$arrMobile[2]?>" maxlength="4"></td>
	</tr>
	
	<!--tr>
		<th>주 소</th>
		<td class="space-left"> 
		  <div style="margin-bottom:3px;">
			<input type="text" id="postcode" name="zip" placeholder="우편번호" class="input" style="width:80px;" maxlength="5" readonly value="<?=$arrInfo["list"][0]['zip']?>" />
			<a href="javascript:execDaumPostcode();"><img src="/common/images/button_zip_search.gif" width="79" height="18" border="0" class="input_button" align="absmiddle"></a>
		  </div>
		  <div style="margin-bottom:3px;"><input name="address" id="address" type="text" class="input" size="50" style="width:90%" value="<?=$arrInfo["list"][0]['address']?>"></div>
		  <div><input name="address_ext" type="text" id="address2" class="input" size="50" style="width:90%" value="<?=$arrInfo["list"][0]['address_ext']?>"></div>
		</td>
	</tr-->
	<tr>
	  <th>최대 주문대수</th>
	  <td class="space-left">
		<select name="etc_5">
			<option value="">대수선택</option>
			<?for ($i=1;$i<51;$i++) {?>
			<option value="<?=$i?>"><?=$i?>건</option>
			<?}?>
		</select>
	  </td>
	</tr>
	<tr>
	  <th>할인기준</th>
	  <td class="space-left"><input name="etc_6" type="text" class="input" style="width:60px" value="0" onkeyup="inNumber(this)"> 대 이상 주문시</td>
	</tr>
	<tr>
	  <th>할인율</th>
	  <td class="space-left"><input name="etc_7" type="text" class="input" style="width:60px" value="0" onkeyup="inNumber(this)"> %할인</td>
	</tr>
	
  </tbody>
</table>

<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="회원등록" style="font-weight:bold" /></span>
	</div>
</div>	
</form>
</div>
</div>

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>