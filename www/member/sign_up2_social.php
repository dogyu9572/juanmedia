<?php include("../inc/header.php"); ?>
<?
@session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/_api/_NICE/niceapi_token_inc.php";
$_SESSION["SEAR_TYPE"] = "social_join";

if($_SESSION['social']['id'] == ""){
	jsGo("/","","세션이 만료되었습니다. 다시시도해주세요.");
	exit();
}else{
	$userName	= $_SESSION['social']['name'];
	$userEmail	= $_SESSION['social']['email'];
}
?>
<script type="text/javascript">
<!--
function fnNicePopup(){
	fnPopup();	// 리얼
}
//-->
</script>
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
		$.get("/module/member/ajax_check_nickname.php", {nick_name: uid},
		function(data){
			if(data=="0"){

			}else if(data=="1"){
				alert('이미 사용중인 전화번호입니다.');
				location.href='/member/login.php';
			}else{
				alert('오류가 발생하였습니다. 다시 시도해 주세요.');
			}
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
/*통합회원 검색*/
function userCheck(){
	var frm = document.memberForm;
	var userName = frm.user_name.value;
	var userMobile = frm.mobile.value;

	$.post("/module/member/ajax_check_userinfo.php", {user_mobile : userMobile},
	function(data){
		//	alert(data)
		if(data!="N"){
			if(confirm(userName+'님 이미 일반회원으로 가입되어 있습니다. SNS 회원으로 통합하시겠습니까?')) {
				userUnite(data);
			}else{
				//	alert('로그인페이지로 이동');	//테스트후 삭제
				location.href='/member/login.php';
			}
		}
	});
}
/*통합회원 승인*/
function userUnite(uniteID){
	var frm = document.memberForm;
	var userType = frm.join_type.value;	
	//	alert(userType);

	$.post("/module/member/ajax_check_userunite.php", {user_type : userType, user_uniteid : uniteID},
	function(data){
		if(data!="Y"){
			alert('계정 통합 실패. 관리자에게 문의해 주세요.');
		}else{
			alert('계정 통합이 정상 처리되었습니다.');
			location.href='/member/login.php';
		}
	});
}
</script>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner inner_in">
		<div class="ctit">회원가입</div>

		<ul class="mem_area join_step">
			<!-- <li class="i1 onf"><i></i><div class="step">STEP 01</div><p>휴대폰 본인인증</p></li> -->
			<li class="i2 on"><i></i><div class="step">STEP 01</div><p>가입정보 입력 및 약관 동의</p></li>
			<li class="i3"><i></i><div class="step">STEP 02</div><p>회원가입 완료</p></li>
		</ul>

		<div class="stit">정보입력 및 이용약관 동의 <p class="abso">* 는 필수 입력 사항입니다.</p></div>
		<form name="memberForm" method="post" action="/module/member/member_evn.php" ENCTYPE="multipart/form-data">
			<input type="hidden" name="evnMode" value="social_join" />
			<input type="hidden" id="dupcheck" name="dupcheck">
			<input type="hidden" id="emailcheck" name="emailcheck">
			<input type="hidden" id="nickcheck" name="nickcheck">
			<input type="hidden" name="member_level" value="1" />
			<input type="hidden" name="returnUrl" value="/member/sign_up_end.php" />	
			<input type="hidden" name="join_type" value="<?=$_SESSION['social']['type']?>">
			<input type="hidden" name="nick_name" id="nickname" value="<?=$nickName?>">
		<div class="tbl tbl_tal mo_break">
			<table>
				<tbody>
				    <tr>
						<th>휴대폰 번호<?## [<a href="javascript:void(0);" onclick="userCheck()">test</a>]?> <span>*</span></th>
						<td>
							<div class="flex inbtn">
								<button type="button" class="btn" onclick="fnNicePopup()">본인인증</button>
								<input type="text" class="text" name="mobile" id="mobile" value="<?=$userPhone?>" readonly placeholder="본인인증을 진행해주세요.">
								
							</div>
						</td>
					</tr>
					<tr>
						<th>이름<span>*</span></th>
						<td><input type="text" class="text w100p" name="user_name" id="user_name" value="<?=$userName?>" readonly></td>
					</tr>
					
					<tr>
						<th>이메일<span>*</span></th>
						<td><input type="text" class="text w100p" name="email" value="<?=$userEmail?>" maxlength="50" placeholder="이메일을 입력해주세요."></td>
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