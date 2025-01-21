<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

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

?>
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
	if (frm.user_id.value.length < 2){
		alert("ID를 입력해 주세요.");
		frm.user_id.focus();
		return false;
	}
	if (frm.user_name.value.length < 2){
		alert("이름을 입력해 주세요.");
		frm.user_name.focus();
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
}

function inNumber(str){
	// 숫자만 입력
	str.value = str.value.replace(/[^0-9]/g,"");	
}
</script>
<div class="container">

	<div class="title">회원 등록</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="memberForm" method="post" action="member_evn.php" onsubmit="return checkForm(this)">
		<input type="hidden" name="evnMode" value="insert">
		<input type="hidden" name="rt_url" value="<?=$_REQUEST['listURL']?>">

		<div class="tit">회원정보 <i>*</i></div>
		<table>
			<tr>
				<th>회원등급</th>
				<td><div class="inputs">
					<select name="user_level">
					<option value="">등급선택</option>
					<?for ($i=0;$i<$arrLevel['total'];$i++) {?>
					<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]["user_level"]?" selected":""?> selected><?=$arrLevel['list'][$i]['level_name']?></option>
					<?}?>
					</select>
				</div></td>
			</tr>
			<tr>
				<th>ID(이메일주소)</th>
				<td><div class="inputs"><input type="text" class="w4" name="user_id" maxlength="50"></div></td>
			</tr>	
			<tr>
				<th>이름</th>
				<td><div class="inputs"><input type="text" class="w4" name="user_name" maxlength="50"></div></td>
			</tr>	
			<tr>
				<th>비밀번호</th>
				<td><div class="inputs"><input type="password" class="w4" name="user_pw" maxlength="50" value="12345678"><em>&nbsp;기본세팅값 : 12345678</em></div></td>
			</tr>	
			<tr>
				<th>비밀번호확인</th>
				<td><div class="inputs"><input type="password" class="w4" name="user_pw2" maxlength="50" value="12345678"><em>&nbsp;기본세팅값 : 12345678</em></div></td>
			</tr>
			<tr>
				<th>휴대폰번호</th>
				<td>
					<div class="inputs">
						<input type="text" class="w1" name="mobile_1" value="<?=$arrMobile[0]?>" maxlength="4">
						<em class="w1">-</em>
						<input type="text" class="w1" name="mobile_2" value="<?=$arrMobile[1]?>" maxlength="4">
						<em class="w1">-</em>
						<input type="text" class="w1" name="mobile_3" value="<?=$arrMobile[2]?>" maxlength="4">
					</div>
				</td>
			</tr>
			<tr>
				<th>구분</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="a_class" value="1" checked><i></i>1</label>
					<label class="radio"><input type="radio" name="a_class" value="2"	><i></i>2</label> 
					<label class="radio"><input type="radio" name="a_class" value="3"	><i></i>3</label> 
				</div></td>
			</tr>
		</table>		

		<div class="btns">
			<a href="member.php" class="btn btn_list">목록보기</a>
			<button class="btn btn_save" type="submit">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->
</div>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
});
//]]>
</script>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>