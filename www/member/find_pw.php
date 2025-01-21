<?php $gNum="09"; $sNum="04"; $gName="회원"; $sName="비밀번호 찾기"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?php
@session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/_api/_NICE/niceapi_token_inc.php";
$_SESSION["SEAR_TYPE"] = "find_pw";
?>
<script>
function fnNicePopup(){
	fnPopup();	// 리얼
}
</script>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner">
		<div class="ctit"><?=$sName?></div>

		<div class="mem_area bg_gray join_wrap find_pw">
			<p class="tac">SNS 회원은 비밀번호 찾기가 제공되지 않습니다.<br><strong>각 SNS 서비스 내에서 확인</strong>해주세요.</p>
			<button type="submit" class="btn" onclick="fnNicePopup()">본인인증</button>
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>