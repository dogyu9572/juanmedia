<?php include("../inc/header.php"); ?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg notice">
				<div class="inner">
					<div class="enName">NOTICE BOARD</div>
					<div class="korName">게시판</div>
					<div class="lnb">
						<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
						<div class="lnbSub">
							<div class="tit">게시판</div>
							<ul>
								<li><a href="/edu/info.php">미디어교육</a></li>
								<li><a href="/equ/info.php">장비대여</a></li>
								<li><a href="/place/info.php">공간대관</a></li>
								<li><a href="/media/info.php">미디어체험</a></li>
								<li><a href="/center/intro.php">센터안내</a></li>
							</ul>
						</div>
						<div class="lnbSub">
							<div class="tit">자료실</div>
							<ul>
								<li><a href="/cm/notice.php">공지&뉴스</a></li>
								<li><a href="/cm/dataList.php">자유게시판</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->

            <?php
            $boardid = "datalist";
            include $_SERVER["DOCUMENT_ROOT"]."/module/board/board.php";
            ?>

		</div>
		<!-- //Container -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


</body>
</html>



