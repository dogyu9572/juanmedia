<?php include("../inc/header.php"); ?>
<?php $gNum = "05"; $sNum = "01"; $gName = "게시판"; $sName = "공지&뉴스"; ?>

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg notice">
				<div class="inner">
					<div class="enName">NOTICE BOARD</div>
					<div class="korName">게시판</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->

            <?php
            $boardid = "notice";
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



