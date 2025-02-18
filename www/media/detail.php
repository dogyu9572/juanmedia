<?php include("../inc/header.php"); ?>

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

			<!-- subSec -->
			<div class="subSec">
				<div class="inner">
					<!-- detailInfo -->
					<div class="detailInfo video">
						<div class="left">
							<div class="btnBack">
								<a href="list.php">뒤로</a>
							</div>
							<div class="img">
								<img src="/images/thumb4.png" alt="섬네일">
							</div>
						</div>
						<div class="textCont">
							<div class="pointBox">
								<div class="tit black">상영완료</div>
								<div class="tit blue2">상영중</div>
								<div class="tit pink">상영예정</div>
							</div>
							<div class="title"><div class="num">12</div>슈팅걸스</div>
							<div class="info">
								<ul>
									<li>
										<div class="tit">접수기간</div>
										<div class="txt">2024-08-21</div>
									</li>
									<li>
										<div class="tit">상영일</div>
										<div class="txt">2024-08-21</div>
									</li>
									<li>
										<div class="tit">상영시간</div>
										<div class="txt">14:00 ~ 15:40</div>
									</li>
									<li>
										<div class="tit">정보</div>
										<div class="txt">드라마 / 97분</div>
									</li>
									<li>
										<div class="tit">대상</div>
										<div class="txt">선착순무료(45석)</div>
									</li>
									<li>
										<div class="tit">정원</div>
										<div class="txt">10명</div>
									</li>
									<li>
										<div class="tit">위치</div>
										<div class="txt">인천미림극장</div>
									</li>
									<li>
										<div class="tit">감독</div>
										<div class="txt">배효민</div>
									</li>
									<li>
										<div class="tit">출연자</div>
										<div class="txt">
											정웅인, 이비안, 정예진, 정지혜 등
										</div>
									</li>
								</ul>
							</div>
							<div class="btnOrder mobFix">
								<a href="orderVideo.php" class="btnType1">신청하기</a>
							</div>
						</div>
					</div>
					<!-- //detailInfo -->
				</div>
			</div>
			<!-- //subSec -->

			<div class="tabBg">
				
				<!-- tabToggleCont -->
				<div class="tabToggleCont">
					<div class="inner">
						<div class="cont">
							<div class="tit">상세정보</div>
							<div class="img">
								<img src="/images/thumb5.png" alt="교육내용">
								<div class="btnZoom"><img src="/images/ico_zoom.svg" alt="확대"></div>
							</div>
						</div>
					</div>
					<div class="btnCenter">
						<a href="list.php" class="btnType1 black list">목록</a>
					</div>
				</div>
				<!-- //tabToggleCont -->



			</div>

		</div>
		<!-- //Container -->


<!-- 컨텐츠팝업 -->
<div class="contentPop profilePop">
	<div class="bg"></div>
	<div class="popIn">
		<div class="content">
			<div class="popTit">강사 프로필</div>
			<div class="tableType1 borderTd">
				<table>
					<colgroup>
						<col class="no1" />
						<col class="no2" />
						<col class="no3" />
					</colgroup>
					<thead>
						<tr>
							<th>활동기간</th>
							<th>기관명</th>
							<th>교육/활동명<br class="mob"/>(세부내용)</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>2022~현재</td>
							<td>콜로소(Coloso)</td>
							<td>픽셀아트 온라인 클래스 개설</td>
						</tr>
						<tr>
							<td>2022~현재</td>
							<td>콜로소(Coloso)</td>
							<td>픽셀아트 온라인 클래스 개설</td>
						</tr>
						<tr>
							<td>2022~현재</td>
							<td>콜로소(Coloso)</td>
							<td>픽셀아트 온라인 클래스 개설</td>
						</tr>
						<tr>
							<td>2022~현재</td>
							<td>콜로소(Coloso)</td>
							<td>픽셀아트 온라인 클래스 개설</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="closePop">
				<a href="javascript:;" onclick="contentClose()">팝업닫기</a>
			</div>
		</div>
	</div>
</div>
<!-- //컨텐츠팝업 -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


</body>
</html>



