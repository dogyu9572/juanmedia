<?php $gNum="09"; $sNum="02"; $gName="회원"; $sName="회원가입"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

if(!$_SESSION['uName']){
	jsGo("/member/sign_up.php","","본인인증 후 회원가입을 진행해 주세요.");
	exit();
}else{
	$userName	= $_SESSION['uName'];
	$userBirth	= $_SESSION['uBirth'];
	$userPhone	= str_replace("-","",$_SESSION['uMobile']);
	$di			= $_SESSION['uDi'];
	$nickName	= "M".$di."EV";

	$arrBirth1	= substr($userBirth,0,4);
	$arrBirth2	= substr($userBirth,4,2);
	$arrBirth3	= substr($userBirth,6,2);
	$arrMobile1 = substr($userPhone,0,3);
	$arrMobile2 = substr($userPhone,3,4);
	$arrMobile3 = substr($userPhone,7,4);

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$birthDay	= $arrBirth1."-".$arrBirth2."-".$arrBirth3;
	$mobileNum	= $arrMobile1."-".$arrMobile2."-".$arrMobile3;

	$arrInfo = getUserFindMobile($mobileNum);

	//DB해제
	SetDisConn($dblink);

	if($arrInfo["total"]>0) {	
		session_destroy();
		jsGo("/member/sign_up.php","","이미 가입된 정보입니다.");
		exit();
	}
}
?>
<script language="javascript">
var pw_checker = 0;
//아이디 중복체크
function check_id(uid){
	if(uid.length<4){
		alert("아이디는 4자 이상 입력해 주세요.");
		document.memberForm.user_id.focus();
		return ;
	}
	if(uid==""){
		alert('아이디를 입력하신후 클릭해 주세요.');
		document.memberForm.user_id.focus();
	} else {
		$.get("/module/member/ajax_check_id.php", {user_id: uid},
		function(data){
			if(data=="0"){
				alert('사용 가능한 아이디입니다.');
				document.memberForm.dupcheck.value = uid;
			}else if(data=="1"){
				alert('이미 사용 중인 아이디입니다. 다른 아이디를 입력해주세요.');
			}else{
				alert('오류가 발생하였습니다. 다시 시도해 주세요.');
			}
			document.memberForm.user_id.focus();
		});
	}
}
function check_email(uid){
	if(uid==""){
		alert('이메일을 입력하신후 클릭해 주세요.');
		document.memberForm.email.focus();
	} else {
		$.get("/module/member/ajax_check_email.php", {user_email: uid},
		function(data){
			if(data=="0"){
				alert('사용가능한 이메일주소 입니다.');
				document.memberForm.emailcheck.value = uid;
			}else if(data=="1"){
				alert('이미 사용중인 이메일주소 입니다.');
			}else{
				alert('오류가 발생하였습니다. 다시 시도해 주세요.');
			}
			document.memberForm.email.focus();
		});
	}
}
function check_nick(uid){
	if(uid==""){
		alert('닉네임을 입력하신후 클릭해 주세요.');
		document.memberForm.nick_name.focus();
	} else {
		$.get("/module/member/ajax_check_nick.php", {nick_name: uid},
		function(data){
			if(data=="0"){
				alert('사용가능한 닉네임 입니다.');
				document.memberForm.nickcheck.value = uid;
			}else if(data=="1"){
				alert('이미 사용중인 닉네임 입니다.');
			}else{
				alert('오류가 발생하였습니다. 다시 시도해 주세요.');
			}
			document.memberForm.nick_name.focus();
		});
	}
}
function info_nick(uid){
	if(uid.length < 10){
		alert('사업자등록번호를 입력하신 후 클릭해 주세요.');
		document.memberForm.etc_2.focus();
	} else {
		$.get("/module/member/ajax_check_info.php", {info_value: uid},
		function(data){
			if(data=="0"){
				alert('사용가능한 사업자등록번호 입니다.');
				document.memberForm.infocheck.value = uid;
			}else if(data=="1"){
				alert('이미 사용중인 사업자등록번호 입니다.');
			}else{
				alert('오류가 발생하였습니다. 다시 시도해 주세요.');
			}
			document.memberForm.etc_2.focus();
		});
	}
}
function frmCheck(frm){	
	if (frm.user_id.value.length < 4){
		alert("아이디는 4자 이상 입력해 주세요.");
		frm.user_id.focus();
		return ;
	}
	if (frm.user_id.value != frm.dupcheck.value){
		alert("아이디 중복확인을 해주세요.");
		return ;
	}
	if(pw_checker == 0){
		alert('비밀번호 형식을 확인을 해주세요');
		frm.user_pw.focus();
		return ;
	}
	if(frm.user_pw.value != frm.user_pw1.value){
		alert('비밀번호가 다릅니다.');
		frm.user_pw1.focus();
		return ;
	}
	if(frm.user_name.value.length < 1){ //이름
		alert('이름을 입력해 주세요.');
		frm.user_name.focus();
		return ;
	}	
	
	if(frm.mobile.value.length < 1){	// 휴대폰 번호
		alert('연락처를 입력해 주세요.');
		frm.mobile.focus();
		return ;
	}
	if(frm.email.value.length < 1){		// 
		alert('이메일을 입력해 주세요.');
		frm.email.focus();
		return ;
	}
	if(!fnEmailcheck(frm.email.value)){
		frm.email.focus();
		return ;
	}

	if(frm.sms_accept.value == "N" && frm.kakao_accept.value == "N"){
		alert('SMS, 알림톡 중 하나는 수신동의하셔야 합니다.');
		return ;
	}
	
	var agree1 = $("#agree01").is(':checked');
	if(!agree1){
		alert('이용약관에 동의 후 진행이 가능합니다.');
		return;
	}
	var agree2 = $("#agree02").is(':checked');
	if(!agree2){
		alert('개인정보 수집동의에 동의 후 진행이 가능합니다.');
		return;
	}

	frm.submit();
}
function fnEmailcheck(email){
	/*===이메일 유호성 검사===*/
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;	
	
	if (email == '' || !re.test(email)) {
		alert("올바른 이메일 주소를 입력하세요")
		return false;
	}else{
		return true;
	}
}
$(document).ready(function(){
	// 숫자만 입력
	$(".numberOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	});
	// 숫자,- 만 입력
	$(".phoneOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9\-]/g,""));
	});
	// 영문,숫자,특수문자만 입력
	$(".pwdCheck").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z._@\-]/g,""));										  
	});
	// 영문만 입력
	$(".engOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z._@\-]/g,""));
	});
	// 영문 띄어쓰기 만 입력
	$(".engName").on("keyup", function() {
		$(this).val($(this).val().replace(/[^a-zA-Z0-9\s]/gi,""));	
		$(this).val($(this).val().toUpperCase());	
	});
	// 한글만 입력
	$(".hanOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[0-9a-zA-Z!@#$%^&*()_+|\-]/g,""));										  
	});
	// 비밀번호 형식
	$('#user_pw').change(function(){
		//	if(/^(?=.*[a-zA-Z])((?=.*\d))((?=.*\W)).{5,19}$/.exec($(this).val())){		//
		if(/^(?!((?:[A-Za-z]+)|(?:[~!@#$%^&*()_+=]+)|(?:[0-9]+))$)[A-Za-z\d~!@#$%^&*()_+=]{8,16}$/.exec($(this).val())){	// 영문/숫자/특수문자 조합 두가지 이상
			pw_checker = 1;			
		}else{
			pw_checker = 0;
		}
	});		
	// 이용약관 동의
	$("#allAgree").click(function(){
		if($(this).is(':checked')){
			$("#agree01").prop('checked',true);
			$("#agree02").prop('checked',true);
		}else{
			$("#agree01").prop('checked',false);
			$("#agree02").prop('checked',false);
		}
	});
});
</script>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner inner_in">
		<div class="ctit">회원가입</div>

		<ul class="mem_area join_step">
			<li class="i1 onf"><i></i><div class="step">STEP 01</div><p>휴대폰 본인인증</p></li>
			<li class="i2 on"><i></i><div class="step">STEP 02</div><p>가입정보 입력 및 약관 동의</p></li>
			<li class="i3"><i></i><div class="step">STEP 03</div><p>회원가입 완료</p></li>
		</ul>

		<div class="stit">정보입력 및 이용약관 동의 <p class="abso">* 는 필수 입력 사항입니다.</p></div>
		<form name="memberForm" method="post" action="/module/member/member_evn.php" ENCTYPE="multipart/form-data">
			<input type="hidden" name="evnMode" value="join" />
			<input type="hidden" id="dupcheck" name="dupcheck">
			<input type="hidden" id="emailcheck" name="emailcheck">
			<input type="hidden" id="nickcheck" name="nickcheck">
			<input type="hidden" name="member_level" value="1" />
			<input type="hidden" name="returnUrl" value="/member/sign_up_end.php" />	
			<input type="hidden" name="join_type" value="homepage">
			<input type="hidden" name="nick_name" value="<?=$nickName?>">
		<div class="tbl tbl_tal mo_break">
			<table>
				<tbody>
					<tr>
						<th>이름<span>*</span></th>
						<td><input type="text" class="text w100p" name="user_name" value="<?=$userName?>" readonly></td>
					</tr>
					<tr>
						<th>휴대폰 번호<span>*</span></th>
						<td><input type="text" class="text w100p" name="mobile" value="<?=$userPhone?>" readonly></td>
					</tr>
					<tr>
						<th>아이디<span>*</span></th>
						<td>
							<div class="flex inbtn">
								<input type="text" class="text" name="user_id" id="user_id" maxlength="20" placeholder="아이디를 입력해주세요.">
								<button type="button" class="btn" onclick="check_id(document.memberForm.user_id.value);">중복확인</button>
							</div>
						</td>
					</tr>
					<tr>
						<th>비밀번호<span>*</span></th>
						<td><input type="password" class="text w100p" name="user_pw" id="user_pw" maxlength="12" placeholder="8~12자의 영문 대소문자, 숫자, 특수문자 조합"></td>
					</tr>
					<tr>
						<th>비밀번호 확인<span>*</span></th>
						<td><input type="password" class="text w100p" name="user_pw1" maxlength="12" placeholder="비밀번호를 재입력해주세요."></td>
					</tr>
					<tr>
						<th>이메일<span>*</span></th>
						<td><input type="text" class="text w100p" name="email" maxlength="50" placeholder="이메일을 입력해주세요."></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="stit mt">수신 동의 <p class="abso">SMS, 알림톡(카카오톡) 수신을 미동의 하시면 접수 관련 안내를 받으실 수 없습니다.</p></div>
		<div class="tbl tbl_tal susin_area mo_break">
			<table>
				<tbody>
					<tr>
						<th>메일 수신 동의</th>
						<td>
							<div class="flex gap">
								<label class="radio"><input type="radio" name="email_accept" value="Y" checked><i></i>동의</label>
								<label class="radio"><input type="radio" name="email_accept" value="N"><i></i>미동의</label>
							</div>
						</td>
					</tr>
					<tr>
						<th>SMS 수신 동의<span>*</span></th>
						<td>
							<div class="flex gap">
								<label class="radio"><input type="radio" name="sms_accept" value="Y" checked><i></i>동의</label>
								<label class="radio"><input type="radio" name="sms_accept" value="N"><i></i>미동의</label>
							</div>
						</td>
					</tr>
					<tr>
						<th>알림톡(카카오톡) <br>수신 동의<span>*</span></th>
						<td>
							<div class="flex gap">
								<label class="radio"><input type="radio" name="kakao_accept" value="Y" checked><i></i>동의</label>
								<label class="radio"><input type="radio" name="kakao_accept" value="N"><i></i>미동의</label>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<ul class="term_box">
				<li><label class="check"><input type="checkbox" id="agree01"><i></i>(필수) 이용약관에 동의합니다.</label><a href="javascript:void(0);" class="btn_term">전문보기</a></li>
				<li><label class="check"><input type="checkbox" id="agree02"><i></i>(필수) 개인정보 수집에 동의합니다.</label><a href="javascript:void(0);" class="btn_privacy">전문보기</a></li>
			</ul>
		</div>

		<button type="button" class="btn_submit2" onclick="frmCheck(document.memberForm)">가입하기</button>
		</form>
	</div>

</div> <!-- //container -->

<div class="popup pop_terms pop_term">
	<div class="dm"></div>
	<div class="inbox">
		<div class="title">이용약관</div>
		<a href="javascript:void(0);" class="btn_close"></a>
		<div class="gbox coming"><?php include("../terms/txt_terms.php") ?></div>
	</div>
</div>

<div class="popup pop_terms pop_privacy">
	<div class="dm"></div>
	<div class="inbox">
		<a href="javascript:void(0);" class="btn_close"></a>
		<div class="title">개인정보처리방침</div>
		<div class="gbox coming"><?php include("../terms/txt_privacy_policy.php") ?></div>
	</div>
</div>

<script>
$(".btn_term").click(function(){
	$(".pop_term").fadeIn("fast");
	$("html,body").addClass("over_h");
});
$(".btn_privacy").click(function(){
	$(".pop_privacy").fadeIn("fast");
	$("html,body").addClass("over_h");
});
$(".popup .btn_close,.popup .dm").click(function(){
	$(".popup").fadeOut("fast");
	$("html,body").removeClass("over_h");
});
</script>

<?php include("../pub/inc/_footer.php") ?>