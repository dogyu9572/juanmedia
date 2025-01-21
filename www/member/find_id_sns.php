<?php $gNum="09"; $sNum="03"; $gName="회원"; $sName="아이디 찾기"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner inner_in">
		<div class="ctit"><?=$sName?></div>

		<div class="mem_area bg_gray mem_end find_sns">
			<p><strong>홍길동</strong> 회원님은 <strong>카카오 로그인</strong>을 통해 가입하셨습니다.<br><strong>카카오</strong> 로그인을 이용해주세요.</p>
			<a href="/member/login.php" class="btn">로그인</a>
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>