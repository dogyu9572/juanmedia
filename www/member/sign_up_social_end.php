<?php $gNum="09"; $sNum="02"; $gName="회원"; $sName="회원가입"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?
if(!$_SESSION[$_SITE["DOMAIN"]]["JOIN"]["NAME"]){
	jsGo("/");
	exit();
}
?>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner inner_in">
		<div class="ctit"><?=$sName?></div>

		<ul class="mem_area social_join_step">
			<!-- <li class="i1 onf"><i></i><div class="step">STEP 01</div><p>휴대폰 본인인증</p></li> -->
			<li class="i2 onf"><i></i><div class="step">STEP 01</div><p>가입정보 입력 및 약관 동의</p></li>
			<li class="i3 on"><i></i><div class="step">STEP 02</div><p>회원가입 완료</p></li>
		</ul>

		<div class="mem_area bg_gray mem_end join_end">
			<p><strong><?=$_SESSION[$_SITE["DOMAIN"]]["JOIN"]["NAME"]?></strong> 님, 환영합니다!<br>회원가입이 완료되었습니다.</p>
			<a href="/member/login.php" class="btn">로그인</a>
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>