<?php include("../inc/header.php"); ?>
<?php $gNum = "02"; $sNum = "02"; $gName = "장비대여"; $sName = "장비대여신청"; ?>

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg equ">
				<div class="inner">
					<div class="enName">EQUIPMENT RENTAL</div>
					<div class="korName">장비대여</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->

            <?php
            $boardid = "equ";
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



