<?php include("../inc/header.php"); ?>
<?php $gNum = "03"; $sNum = "02"; $gName = "공간대관"; $sName = "공간대관신청"; ?>

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg place">
				<div class="inner">
					<div class="enName">SPACE RENTAL</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->
            <?php
            $boardid = "place";
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



