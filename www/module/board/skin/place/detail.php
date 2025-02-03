<?php include("../inc/header.php"); ?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg place">
				<div class="inner">
					<div class="enName">SPACE RENTAL</div>
					<div class="korName">공간대관</div>
					<div class="lnb">
						<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
						<div class="lnbSub">
							<div class="tit">공간대관</div>
							<ul>
								<li><a href="/edu/info.php">미디어교육</a></li>
								<li><a href="/equ/info.php">장비대여</a></li>
								<li><a href="/media/info.php">미디어체험</a></li>
								<li><a href="/cm/notice.php">게시판</a></li>
								<li><a href="/center/intro.php">센터안내</a></li>
							</ul>
						</div>
						<div class="lnbSub">
							<div class="tit">공간대관신청</div>
							<ul>
								<li><a href="/place/info.php">대관안내</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- subSec -->
			<div class="subSec">
				<div class="inner">
					<div class="btnBack">
						<a href="list.php">뒤로</a>
					</div>
					<!-- eqDetail -->
					<div class="eqDetail detailInfo">
						<div class="img">
							<div class="swiper-wrapper">
								<div class="swiper-slide"><img src="/images/place_thumb1.jpg" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/place_thumb1.jpg" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/place_thumb1.jpg" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/place_thumb1.jpg" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/place_thumb1.jpg" alt="섬네일"></div>
							</div>
							<div class="swiper-pagination"></div>
						</div>
						<div class="textCont">
							<div class="pointBox">
								<div class="tit green">초급</div>
							</div>
							<div class="title">
								오디오 스튜디오
							</div>
							<p class="subTit">조정실과 녹음부스로 나뉘어진 오디오 방송을 위한 공간. 5~6명이 동시에 녹음 가능하며, 개인 혹은 팀단위의 라디오 방송, 팟캐스트 방송제작 가능</p>
							<div class="price">
								<div class="name">대관료</div>
								<div class="money">10,000원 (1시간)</div>
							</div>
							<div class="info">
								<ul>
									<li>
										<div class="tit">대관 / 반납시간</div>
										<div class="txt">10:00~21:00 / 10:00~21:00</div>
									</li>
									<li>
										<div class="tit">점심 / 저녁시간</div>
										<div class="txt">12:00 ~ 13:00 / 18:00 ~ 19:00</div>
									</li>
								</ul>
								<ul>
									<li>
										<div class="tit">정원</div>
										<div class="txt">10명</div>
									</li>
									<li>
										<div class="tit">대관일 선택</div>
										<div class="txt">
											<div class="cmsDate solo">
												<div class="baseInput">
													<input id="st1" readonly type="text" title="시작날짜" value="2024-12-17">
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="tit">대관시간</div>
										<div class="txt">
											<div class="twoSel">
												<div class="baseSel">
													<select>
														<option value="">10:00</option>
														<option value="">10:00</option>
														<option value="">10:00</option>
													</select>
												</div>
												<div class="line">-</div>
												<div class="baseSel">
													<select>
														<option value="">13:00</option>
														<option value="">13:00</option>
														<option value="">일반13:00/option>
													</select>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="totalPrice">
								<div class="nameDate">
									오디오 스튜디오 / 초급 / (3시간)
								</div>
								<div class="price">30,000원</div>
								<a href="javascript:void(0)" class="close"><img src="/images/ico_smClose.svg" alt="닫기"></a>
							</div>
							<div class="btnOrder mobFix">
								<a href="order.php" class="btnType1">대관 신청</a>
							</div>
						</div>
					</div>
					<!-- //eqDetail -->

				</div>
			</div>
			<!-- //subSec -->

			<!-- subSec -->
			<div class="subSec blue last">
				<div class="inner">
					<div class="whiteBox">
						<div class="wTit">대관 신청 안내</div>
						<div class="iconList">
							<ul>
								<li>
									<div class="img"><img src="/images/ico_eq6.svg" alt="아이콘"></div>
									<div class="text">
										<div class="step">STEP 01</div>
										<div class="tit">공간번호 선택</div>
									</div>
								</li>
								<li>
									<div class="img"><img src="/images/ico_eq7.svg" alt="아이콘"></div>
									<div class="text">
										<div class="step">STEP 02</div>
										<div class="tit">대관 일자 선택</div>
									</div>
								</li>
								<li>
									<div class="img"><img src="/images/ico_eq8.svg" alt="아이콘"></div>
									<div class="text">
										<div class="step">STEP 03</div>
										<div class="tit">방문 시간 조정</div>
									</div>
								</li>
								<li>
									<div class="img"><img src="/images/ico_eq9.svg" alt="아이콘"></div>
									<div class="text">
										<div class="step">STEP 04</div>
										<div class="tit">대관신청</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="whiteBox">
						<div class="wTit">이용 안내</div>
						<ul class="textUl mb">
							<li >· 대여시 운용교육은 따로 하지 않습니다. (사용이 가능한 회원만 대여해 주세요)<br />* 공간 대관 운영시간 : 화~금요일 10:00~21:00, 토요일 10:00~17:00</li>
							<li>· 홈페이지를 통해서 최소 2일전에 예약 신청 후, 담당자와 통화 후 예약이 확정됩니다.<br />* 담당자와 통화를 하지 않고 방문 시 공간대여 불가  (032-872-2622)</li>
							<li>· 공간 대여료 결제는 홈페이지, 현금으로 불가능하며, 계좌이체를 통해서만 가능합니다.<br />(주안영상미디어센터 계좌번호 : 신한은행 100-035-698102)</li>
							<li>★ 현재 이용 가능 최대인원 : 10명 </li>
						</ul>	
					</div>
					<div class="whiteBox">
						<div class="wTit">상세 정보</div>
						<ul class="textUl">
							<li>· 주안영상미디어센터의 모든 공간은 상업적인 용도로 대여 하실 수 없습니다.</li>
							<li>· 공간 대여 신청 및 취소는 홈페이지를 통해 최소 2일전에 하셔야 합니다.</li>
							<li>· 담당 직원에게 사용하신 공간 이상 유무를 확인 후 퇴실 가능합니다.</li>
						</ul>	
					</div>
					<div class="whiteBox">
						<div class="wTit">유의사항</div>
						<ul class="textUl">
							<li>· 모든 공간 내 음식물 반입은 금지입니다.</li>
							<li>· 음식물 섭취는 센터 내 로비를 이용해 주시고, 물은 센터 복도에 비치된 정수기를 이용해주세요.</li>
							<li>· 소중하게 공간 이용 후, 정리정돈 부탁드립니다.</li>
							<li>· 사용하신 책상과 의자는 제자리에, 전등, 에어컨, 컴퓨터의 전원은 꼭 꺼주세요.</li>
							<li>· 쓰레기는 가지고 나와 센터 복도에 있는 쓰레기통에 버려주세요.</li>
						</ul>	
					</div>
					<div class="whiteBox">
						<div class="wTit">신청서 작성 <a href="/download/juanmedia.hwp" download="주안영상미디어센터 시설 및 장비 사용(감면) 신청서(구청양식).hwp" class="btnDown"><span>신청서 다운로드</span></a></div>
						<ul class="textUl">
							<li>· 대여 신청 완료 후 별도의 신청서를 작성하여 파일 첨부 또는 해당 이메일로 보내 주시기를 바랍니다.</li>
							<li>· Email : juanmedia@daum.net</li>
						</ul>	
					</div>

					<div class="btnCenter">
						<a href="list.php" class="btnType1 black list">목록</a>
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

<script>
var swiper = new Swiper(".eqDetail .img", {
	pagination: {
		el: ".swiper-pagination",
	},
});
</script>

</body>
</html>