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

$arrEmail = explode("@",$arrInfo["list"][0]['email']);
$arrPhone = explode("-",$arrInfo["list"][0]['phone']);
$arrMobile = explode("-",$arrInfo["list"][0]['mobile']);
$arrFax = explode("-",$arrInfo["list"][0]['fax']);
$arrZip = explode("-",$arrInfo["list"][0]['zip']);
$arrTel = explode("-",$arrInfo["list"][0]['regnum1']);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">회원 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 회원정보 수정</div>
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
/*
	if (frm.solar[0].checked==false && frm.solar[1].checked==false){
		alert("양력/음력을 선택해 주세요.");
		frm.solar[0].focus();
		return false;
	}

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
	if (frm.email_id.value.length < 2){
		alert("이메일을 입력해 주세요.");
		frm.email_id.focus();
		return false;
	}
	if (frm.email_domain.value.length < 2){
		alert("이메일을 입력해 주세요.");
		frm.email_domain.focus();
		return false;
	}
	/*
	if (frm.email_accept[0].checked==false && frm.email_accept[1].checked==false){
		alert("이메일 수신여부를 선택해 주세요.");
		frm.email_accept[0].focus();
		return false;
	}
	*/
}

function setEmailDom(frm,val){
		frm.email_domain.value = val;
		if(val==""){
			frm.email_domain.focus();
		}
}

function zipSearch(tp){
	var obj = window.open('/module/zipcode/zipcode.php?tp='+tp,'주소찾기','width=463, height=305, scrollbars=1');
	obj.focus();
}
function inNumber(str){
	// 숫자만 입력
	str.value = str.value.replace(/[^0-9]/g,"");	
}
$(document).ready(function() {	
	// 숫자만 입력
	$("input:text[numberOnly]").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	});
	// 영문,숫자,특수문자만 입력
	$("input:text[engOnly]").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z._@\-]/g,""));										  
	});
	// 한글만 입력
	$("input:text[hanOnly]").on("keyup", function() {
		$(this).val($(this).val().replace(/[0-9a-zA-Z!@#$%^&*()_+|\-]/g,""));										  
	});
});
</script>


<form name="memberForm" method="post" action="member_evn.php" onsubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="edit">
<input type="hidden" name="user_id" value="<?=$arrInfo["list"][0]['user_id']?>">
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
		<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]["user_level"]?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
		<?}?>
		</select>
		</td>
	</tr>
	<tr>
		<th>아이디</th>
		<td class="space-left"><?=$arrInfo["list"][0]["user_id"]?></td>
	</tr>
	<tr>
		<th>비밀번호</th>
		<td class="space-left"><input name="user_pw" type="password" class="input" size="20"></td>
	</tr>
	<tr>
		<th>비밀번호 확인</th>
		<td class="space-left"><input name="user_pw2" type="password" class="input" size="20"></td>
	</tr>
	<tr>
		<th>이 름</th>
		<td class="space-left"><?=$arrInfo["list"][0]['user_name']?></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td class="space-left"><?=$arrInfo["list"][0]['email']?></td>
	</tr>
	<!--tr>
		<th>소속</th>
		<td class="space-left"><input name="job" type="text" class="input" size="20" value="<?=$arrInfo["list"][0]['user_name']?>"></td>
	</tr>
	<tr>
	  <th>생년월일</th>
	  <td class="space-left"><input type="text" name="birth" maxlength="10" size="12" class="input" readonly value="<?=$arrInfo["list"][0]['birth']?>"/>
	  </td>
	</tr>
	<tr>		
		<th>성별</th>
		<td class="space-left"><input name="sex" type="radio" value="M" class="input_button"<?=$arrInfo["list"][0]['sex']=="M"?" checked":""?>>남 <input name="sex" type="radio" value="F" class="input_button"<?=$arrInfo["list"][0]['sex']=="F"?" checked":""?>>여</td>
	</tr>
	
	<tr>
		<th>전화번호</th>
		<td class="space-left"><input name="phone_1" type="text" class="input" style="width:40px" value="<?=$arrPhone[0]?>">
			-
			<input name="phone_2" type="text" class="input" style="width:40px" value="<?=$arrPhone[1]?>">
			-
			<input name="phone_3" type="text" class="input" style="width:40px" value="<?=$arrPhone[2]?>"></td>
	</tr-->
	<tr>
		<th>휴대번호</th>
		<td class="space-left"><input name="mobile_1" type="text" class="input" style="width:40px" value="<?=$arrMobile[0]?>">
			-
			<input name="mobile_2" type="text" class="input" style="width:40px" value="<?=$arrMobile[1]?>">
			-
			<input name="mobile_3" type="text" class="input" style="width:40px" value="<?=$arrMobile[2]?>"></td>
	</tr>
	<tr>
		<th>주 소</th>
		<td class="space-left"> 
		  <div style="margin-bottom:3px;">
			<input type="text" id="postcode" name="zip" placeholder="우편번호" class="input" style="width:80px;" maxlength="5" readonly value="<?=$arrInfo["list"][0]['zip']?>" />
			<a href="javascript:execDaumPostcode();"><img src="/common/images/button_zip_search.gif" width="79" height="18" border="0" class="input_button" align="absmiddle"></a>
		  </div>
		  <div style="margin-bottom:3px;"><input name="address" id="address" type="text" class="input" size="50" style="width:90%" value="<?=$arrInfo["list"][0]['address']?>"></div>
		  <div><input name="address_ext" type="text" id="address2" class="input" size="50" style="width:90%" value="<?=$arrInfo["list"][0]['address_ext']?>"></div>
		</td>
	</tr>
	<!-- <tr>
	  <th>SMS 수신여부</th>
	  <td class="space-left">
	  <input id="sms_accept_y" name="sms_accept" type="radio" value="Y"<?=$arrInfo["list"][0]["sms_accept"]=="Y"?" checked":""?>> 예
	  <input name="sms_accept" type="radio" value="N"<?=$arrInfo["list"][0]["sms_accept"]=="N"?" checked":""?>> 아니오
	  </td>
	</tr>
	<tr>
		<th>SMS 수신 동의일시</th>
		<td class="space-left"><?=$arrInfo["list"][0]['sms_accept_date']?></td>
	</tr> -->

	<!--tr>
		<th>Email 수신여부</th>
		<td class="space-left"><input name="email_accept" type="radio" class="input_button" value="Y"<?=$arrInfo["list"][0]['email_accept']=="Y"?" checked":""?>>예 <input name="email_accept" type="radio" class="input_button" value="N"<?=$arrInfo["list"][0]['email_accept']=="N"?" checked":""?>>아니오</td>
	</tr>
	<tr>
		<th>메일수신 동의일시</th>
		<td class="space-left"><?=$arrInfo["list"][0]['email_accept_date']?></td>
	</tr-->
	<!-- tr>
	  <th>키즈몰 ID</th>
	  <td class="space-left"><input name="etc_1" type="text" class="input" style="width:10%" value="<?=($arrInfo["list"][0]['etc_1'])?>"></td>
	</tr>
	<tr>
	  <th>기타4</th>
	  <td class="space-left"><input name="etc_4" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_4'])?>"></td>
	</tr>
	<tr>
	  <th>기타5</th>
	  <td class="space-left"><input name="etc_5" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_5'])?>"></td>
	</tr>
	<tr>
	  <th>기타6</th>
	  <td class="space-left"><input name="etc_6" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_6'])?>"></td>
	</tr>
	<tr>
	  <th>기타7</th>
	  <td class="space-left"><input name="etc_7" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_7'])?>"></td>
	</tr>
	<tr>
	  <th>기타8</th>
	  <td class="space-left"><input name="etc_8" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_8'])?>"></td>
	</tr>
	<tr>
	  <th>기타9</th>
	  <td class="space-left"><input name="etc_9" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_9'])?>"></td>
	</tr>
	<tr>
	  <th>기타10</th>
	  <td class="space-left"><input name="etc_10" type="text" class="input" style="width:30%" value="<?=($arrInfo["list"][0]['etc_10'])?>"></td>
	</tr-->


	<tr>
		<th>로그인 횟수</th>
		<td class="space-left"><?=number_format($arrInfo["list"][0]['login_count'])?></td>
	</tr>
	<tr>
		<th>최근로그인</th>
		<td class="space-left"><?=$arrInfo["list"][0]['login_last']?></td>
	</tr>
	<tr>
		<th>업데이트일</th>
		<td class="space-left"><?=$arrInfo["list"][0]['udate']?></td>
	</tr>
	<tr>
		<th>회원가입일</th>
		<td class="space-left"><?=$arrInfo["list"][0]['wdate']?></td>
	</tr>
  </tbody>
</table>
<!-- E 개인정보입력 -->
<br />
<?if($arrInfo["total_baby"] > 0):?>
<h3 class="admin-title-middle">자녀정보</h3>
<?for($i=0;$i<$arrInfo["total_baby"];$i++){?>
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<tr>
		<th>이름(태명)</th>
		<td class="space-left"><?=$arrInfo["baby"][$i]['babyname']?> <? if($arrInfo["baby"][$i]['children']=="Y") { echo "(대표자녀)"; }?>
		</td>
	</tr>
	<tr>
		<th>출생여부</th>
		<td class="space-left"><?=$arrInfo["baby"][$i]['prenatal']=="Y"?"출생":"출생전"?>
		</td>
	</tr>
	<tr>
		<th>성별</th>
		<td class="space-left"><?=$_SITE["SHOP"]["SEX"][$arrInfo["baby"][$i]['sex']]?></td>
	</tr>
	<tr>
		<th>아이생일</th>
		<td class="space-left"><?=$arrInfo["baby"][$i]['birth']?></td>
	</tr>
  </tbody>
</table>
<?} endif;?>
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