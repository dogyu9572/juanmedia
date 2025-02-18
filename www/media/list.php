<?php include("../inc/header.php"); ?>
<?php $gNum = "04"; $sNum = "04"; $gName = "미디어 체험"; $sName = "상영회신청"; ?>

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg media">
				<div class="inner">
					<div class="enName">BRUNCH MOVIE TALK</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->
            <?php
            $boardid = "video";
            include_once $_SERVER["DOCUMENT_ROOT"]."/module/board/board.php";
            ?>

		</div>
		<!-- //Container -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


</body>
</html>



