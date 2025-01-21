<?php $gNum="09"; $sNum="04"; $gName="회원"; $sName="비밀번호 찾기"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?php 
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"]	= "";				## 접수용 아이디
?>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner">
		<div class="ctit"><?=$sName?></div>

		<div class="mem_area bg_gray join_wrap find_pw_end">
			<p class="tac">비밀번호 변경이 완료되었습니다.</p>
			<a href="/member/login.php" class="btn">로그인</a>
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>