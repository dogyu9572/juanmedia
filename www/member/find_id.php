<?php $gNum="09"; $sNum="03"; $gName="회원"; $sName="아이디 찾기"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?php
@session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/_api/_NICE/niceapi_token_inc.php";
$_SESSION["SEAR_TYPE"] = "find_id";
?>
<script>
function fnNicePopup(){
	fnPopup();	// 리얼
}
</script>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner">
		<div class="ctit"><?=$sName?></div>

		<div class="mem_area bg_gray join_wrap">
			<p class="tac"><strong>휴대폰 본인인증</strong>을 통해 아이디 찾기를 진행합니다.</p>
			<button type="submit" class="btn" onclick="fnNicePopup()">휴대폰 본인인증</button>
			<!-- sns로 가입했을 경우 -->
			<!-- <button type="submit" class="btn" onclick="location.href='/member/find_id_sns.php'">휴대폰 본인인증</button> -->
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>