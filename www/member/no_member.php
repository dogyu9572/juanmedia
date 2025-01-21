<?php $gNum="10"; $sNum="01"; $gName="비회원 접수 조회"; $sName="비회원 접수 조회"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/_api/_NICE/niceapi_token_inc.php";
$_SESSION["SEAR_TYPE"] = "receipt";
?>
<script>
function fnNicePopup(){
	fnPopup();	// 리얼
}
</script>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner">
		<div class="no_member glbox">
			<div class="stit">휴대폰 본인인증을 진행해주세요.</div>
			<p>비회원 접수 내역 조회를 위해 휴대폰 본인인증을 진행합니다.</p>
			<button class="btn_submit2" onclick="fnNicePopup()">휴대폰 본인인증</button>
			<p class="btm">회원인 경우 우측 상단의 [로그인]을 클릭 후, <br class="pc_vw">회원 로그인 후 마이페이지에서 접수 내역을 확인하실 수 있습니다.</p>
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>