<?php include("../inc/header.php"); ?>
<?php $gNum = "06"; $sNum = "07"; $gName = "센터안내"; $sName = "찾아오시는 길"; ?>

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg center">
				<div class="inner">
					<div class="enName">CENTER INFORMATION</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- pageTitle -->
			<div class="pageTitle inner">찾아오시는 길</div>
			<!-- //pageTitle -->


			<!-- subSec -->
			<div class="subSec pt0 last">
				<div class="map">
					<!-- * 카카오맵 - 지도퍼가기 -->
					<!-- 1. 지도 노드 -->
					<div id="daumRoughmapContainer1732511824676" class="root_daum_roughmap root_daum_roughmap_landing"></div>

					<!--
						2. 설치 스크립트
						* 지도 퍼가기 서비스를 2개 이상 넣을 경우, 설치 스크립트는 하나만 삽입합니다.
					-->
					<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>

					<!-- 3. 실행 스크립트 -->
					<script charset="UTF-8">
						new daum.roughmap.Lander({
							"timestamp" : "1732511824676",
							"key" : "2mbqg",
							"mapWidth" : "1920",
							"mapHeight" : "360"
						}).render();
					</script>
				</div>

				<div class="inner">

					<div class="locationWrap">
						<div class="logo"><img src="/images/ico_logo.svg" alt="주안영상미디어센터"></div>
						<div class="text">
							<div class="line">
								<div class="left">
									<div class="bIco email"></div>
									<div class="title">주소</div>
								</div>
								<div class="right">
									인천광역시 미추홀구 석바위로 68<br class="mob" />(주안동 173-1) 주안필프라자 7층, 8층
									<p>사랑병원 인근 / 주안역 지하상가 출구 8번 / <br class="mob" />시민공원역 지하상가 출구 2번 
									<span class="etc">* 주차 공간이 협소하오니, 대중교통을 이용해주시기 바랍니다.</span></p>
								</div>
							</div>
							<div class="line">
								<div class="left">
									<div class="bIco tel"></div>
									<div class="title">전화</div>
								</div>
								<div class="right">032-872-2622</div>
							</div>
							<div class="line">
								<div class="left">
									<div class="bIco fax"></div>
									<div class="title">팩스</div>
								</div>
								<div class="right">032-873-2622</div>
							</div>
							<div class="line">
								<div class="left">
									<div class="bIco email"></div>
									<div class="title">메일</div>
								</div>
								<div class="right">juanmedia@daum.net</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- //subSec -->

		</div>
		<!-- //Container -->


<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


</body>
</html>



