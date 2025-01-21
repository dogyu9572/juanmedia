<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_POST['evnMode']=="find_id"){
	$pn = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_1'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_2'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_3']);
	$arrInfo = getUserFindMobile($pn);
}

if($_POST['evnMode']=="find_pw"){
	
	$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id']));

	$arrMailInfo = getMailConfig("passwd");
	sendMailPasswdInfo($arrInfo, $arrMailInfo);
}

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function checkFormID(frm){
	if (frm.mobile_1.value.length < 1){
		alert("핸드폰번호를 입력해 주세요.");
		frm.mobile_1.focus();
		return ;
	}
	if (frm.mobile_2.value==""){
		alert("휴대전화번호를 입력해 주세요.");
		frm.mobile_2.focus();
		return ;
	}
	if (frm.mobile_3.value==""){
		alert("휴대전화번호를 입력해 주세요.");
		frm.mobile_3.focus();
		return ;
	}
	if (frm.cert_num.value==""){
		alert("인증번호를 입력해 주세요.");
		frm.cert_num.focus();
		return ;
	}
	if (frm.cert_num.value != frm.idCertNum.value){
		alert("인증번호가 일치하지 않습니다.");
		return ;
	}

	frm.submit();
}

function checkFormPW(frm){
	if (frm.user_id.value==""){
		alert("아이디(이메일)을 입력해 주세요.");
		frm.user_id.focus();
		return ;
	}
	if (frm.mobile_1.value.length < 1){
		alert("핸드폰번호를 입력해 주세요.");
		frm.mobile_1.focus();
		return ;
	}
	if (frm.mobile_2.value==""){
		alert("휴대전화번호를 입력해 주세요.");
		frm.mobile_2.focus();
		return ;
	}
	if (frm.mobile_3.value==""){
		alert("휴대전화번호를 입력해 주세요.");
		frm.mobile_3.focus();
		return ;
	}
	if (frm.cert_num.value==""){
		alert("인증번호를 입력해 주세요.");
		frm.cert_num.focus();
		return ;
	}
	if (frm.cert_num.value != frm.idCertNum.value){
		alert("인증번호가 일치하지 않습니다.");
		return ;
	}

	frm.submit();
}

function sendAcc(frm){

	if (frm.mobile_1.value.length < 1){
		alert("핸드폰번호를 입력해 주세요.");
		frm.mobile_1.focus();
		return ;
	}
	if (frm.mobile_2.value==""){
		alert("휴대전화번호를 입력해 주세요.");
		frm.mobile_2.focus();
		return ;
	}
	if (frm.mobile_3.value==""){
		alert("휴대전화번호를 입력해 주세요.");
		frm.mobile_3.focus();
		return ;
	}
	var pn =  frm.mobile_1.value+"-"+frm.mobile_2.value+"-"+frm.mobile_3.value;

	$.get("/module/member/ajax_check_mobile.php", {mobile: pn},
	function(data){
		if(data){
			alert("인증번호가 전송되었습니다.");
			frm.idCertNum.value = data;
			frm.cert_num.focus();
		}else{
			alert('해당 핸드폰번호는 회원가입된 번호가 아닙니다.\n\n확인후 다시 시도해주시기 바랍니다.');
			frm.mobile_1.focus();
		}
	});
}

</script>


	<div id="sub_container">
		<div class="content">
			<div class="location">
				<p class="local"><span class="home"></span><span class="current">ID/PW Search</span></p>
			</div>
			<!-- //location -->
			
			<div class="con">
			<!-- 내용 : s -->
				
				<h2>ID/PW Search</h2>
				<div class="member">
					<div class="IdpwFind">
						<div class="areaL">
							<form name="findFrmID" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
							<input type="hidden" name="evnMode" value="find_id">
							<input type="hidden" name="goPage" value="Find">
							<input type="hidden" name="idCertNum" value="">
							<dl>
								<dt>아이디(이메일)를 잊으셨나요?</dt>
								<dd>frienpi 사이트에 가입 시 입력한 휴대폰 번호를 입력하시고<br />
									인증 후 아이디(이메일)를 확인하시기 바랍니다.</dd>
							</dl>
							<?if($_POST['evnMode']=="find_id" && $arrInfo["total"] > 0){?>
								<? if($arrInfo["list"][0]['user_level']=="90") {?>
								<div class="btnC"><?=$arrInfo["list"][0][user_name]?>님은 탈퇴회원입니다.</div>
								<?}else {?>
								<div class="btnC"><?=$arrInfo["list"][0][user_name]?>님 아이디(이메일)는 [ <strong><?=$arrInfo["list"][0][user_id]?></strong> ] 입니다.</div>
								<?}?>
							<?}else{?>
							<ul>
								<li>
									<span>휴대폰 번호</span>
									<input type="text" id="mobile_1" name="mobile_1" value="" style="width:75px;" maxlength="4" /> -
									<input type="text" id="mobile_2" name="mobile_2" value="" style="width:106px;" maxlength="4" /> -
									<input type="text" id="mobile_3" name="mobile_3" value="" style="width:106px;" maxlength="4" /> <a href="javascript:sendAcc(document.findFrmID)" class="checkBtn">인증번호 발송</a>
								</li>
								<li>
									<span>인증번호</span>
									<input type="text" id="cert_num" name="cert_num" value="" style="width:313px;" />
								</li>
							</ul>
							<div class="btnC">
								<a href="javascript:checkFormID(document.findFrmID)" class="btn_gray4">아이디 찾기</a>
							</div>	
							<?}?>
							</form>
						</div>
						<!-- //areaL -->

						<div class="areaR">
							<form name="findFrmPW" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
							<input type="hidden" name="goPage" value="Find">
							<input type="hidden" name="evnMode" value="find_pw">
							<input type="hidden" name="idCertNum" value="">
							<dl>
								<dt>비밀번호를 잊으셨나요?</dt>
								<dd>frienpi 사이트 아이디(이메일)를 입력하시면 인증 후 <br />
									아이디(이메일)로 임시비밀번호를 발송해 드립니다.</dd>
							</dl>
							<?if($_POST['evnMode']=="find_pw" && $arrInfo["total"] > 0){?>
								<? if($arrInfo["list"][0]['user_level']=="90") {?>
								<div class="btnC"><?=$arrInfo["list"][0][user_name]?>님은 탈퇴회원입니다.</div>
								<?}else {?>
								<div class="btnC"><?=$arrInfo["list"][0][user_name]?>님 이메일(아이디)로 임시비밀번호를 발송하였습니다.<br />로그인후 비밀번호를 변경하시기 바랍니다.</div>
								<?}?>
							<?}else if($_POST['evnMode']=="find_pw" && $arrInfo["total"] == 0){?>
							<div class="btnC">해당하는 정보가 없습니다.</div>
							<div class="btnC">
								<a href="javascript:history.back()" class="btn_gray4">확 인</a>
							</div>
							<?}else{?>
							<ul>
								<li>
									<span>아이디(이메일)</span>
									<input type="text" id="user_id" name="user_id" value="" style="width:313px;" />
								</li>
								<li>
									<span>휴대폰 번호</span>
									<input type="text" id="mobile_1" name="mobile_1" value="" style="width:75px;" /> -
									<input type="text" id="mobile_2" name="mobile_2" value="" style="width:106px;" /> -
									<input type="text" id="mobile_3" name="mobile_3" value="" style="width:106px;" /> <a href="javascript:sendAcc(document.findFrmPW)" class="checkBtn">인증번호 발송</a>
								</li>
								<li>
									<span>인증번호</span>
									<input type="text" id="cert_num" name="cert_num" value="" style="width:313px;" />
								</li>
							</ul>
							<div class="btnC">
								<a href="javascript:checkFormPW(document.findFrmPW)" class="btn_gray4">인증하기</a>
							</div>
							<?}?>
							</form>
						</div>
						<!-- //areaR -->
					</div>
					<!-- //IdpwFind -->
				</div>
				<!-- //member -->
				
			<!-- 내용 : e -->	
			</div>
			<!-- //con -->
		</div>
		<!--//content --> 
	</div>
