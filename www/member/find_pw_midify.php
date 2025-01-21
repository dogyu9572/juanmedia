<?php $gNum="09"; $sNum="04"; $gName="회원"; $sName="비밀번호 찾기"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?php
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$nick_name = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"];

$arrList = getUserInfoNnickname($nick_name);

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	if($arrList["list"][0]["join_type"] != "homepage"){ // 소셜회원일 경우	
		jsGo("/member/login.php",'',"해당 회원은 소셜 회원입니다.");
	}
}else{
	jsGo("/member/login.php",'',"회원정보가 없습니다.");
}
?>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner">
		<div class="ctit"><?=$sName?></div>
		<div class="mem_area bg_gray">
			<div class="inputs">
				<p class="tac">새로 사용할 비밀번호를 입력해주세요.</p>
				<form name="frmMember" action="/module/member/member_evn.php" method="post" onsubmit="return frmSubmit(this)">
					<input type="hidden" name="evnMode" value="pw_change">
					<input type="hidden" name="returnURL" value="/member/find_pw_end.php">
					<input type="password" name="user_pw" id="user_pw" class="text w100p" placeholder="8~12자의 영문 대소문자, 숫자, 특수문자 조합">
					<input type="password" name="user_pw1" class="text w100p mb0" placeholder="비밀번호를 재입력해주세요.">
					<button type="submit" class="btn mt3">비밀번호 변경</button>
				</form>
			</div>
		</div>
	</div>

</div> <!-- //container -->
<script>
	let pw_checker = 0;
	$(document).ready(function(){
		// 비밀번호 형식
		$('#user_pw').on("input",function(){
			//	if(/^(?=.*[a-zA-Z])((?=.*\d))((?=.*\W)).{5,19}$/.exec($(this).val())){		//
			if(/^(?!((?:[A-Za-z]+)|(?:[~!@#$%^&*()_+=]+)|(?:[0-9]+))$)[A-Za-z\d~!@#$%^&*()_+=]{8,16}$/.exec($(this).val())){	// 영문/숫자/특수문자 조합 두가지 이상
				pw_checker = 1;			
			}else{
				pw_checker = 0;
			}
		});		
	});
	function frmSubmit(frm){
		if(pw_checker == 0){
			alert('비밀번호 형식을 확인을 해주세요');
			frm.user_pw.focus();
			return false;
		}else if(frm.user_pw.value != frm.user_pw1.value){
			alert('비밀번호가 다릅니다.');
			frm.user_pw1.focus();
			return false;
		}
	}
</script>

<?php include("../pub/inc/_footer.php") ?>