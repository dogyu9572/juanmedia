<?php include("../inc/header.php"); ?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg equ">
				<div class="inner">
					<div class="enName">EQUIPMENT RENTAL</div>
					<div class="korName">장비대여</div>
					<div class="lnb">
						<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
						<div class="lnbSub">
							<div class="tit">장비대여</div>
							<ul>
								<li><a href="/edu/info.php">미디어교육</a></li>
								<li><a href="/place/info.php">공간대관</a></li>
								<li><a href="/media/info.php">미디어체험</a></li>
								<li><a href="/cm/notice.php">게시판</a></li>
								<li><a href="/center/intro.php">센터안내</a></li>
							</ul>
						</div>
						<div class="lnbSub">
							<div class="tit">장비대여신청</div>
							<ul>
								<li><a href="/equ/info.php">대여안내</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- subSec -->
			<div class="subSec ">
				<div class="inner">
					<div class="btnBack">
						<a href="list.php">뒤로</a>
					</div>
					<!-- eqDetail -->
					<div class="eqDetail detailInfo">
						<div class="img">
							<div class="swiper-wrapper">
								<div class="swiper-slide"><img src="/images/equ_thumb1.png" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/equ_thumb1.png" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/equ_thumb1.png" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/equ_thumb1.png" alt="섬네일"></div>
								<div class="swiper-slide"><img src="/images/equ_thumb1.png" alt="섬네일"></div>
							</div>
							<div class="swiper-pagination"></div>
						</div>
						<div class="textCont">
							<div class="pointBox">
								<div class="tit">촬영장비</div>
								<div class="tit green">초급</div>
							</div>
							<div class="title">HXR-MC88</div>
							<div class="price">
								<div class="name">대여료</div>
								<div class="money">15,000원 (1일)</div>
							</div>
							<div class="info">
								<ul>
									<li>
										<div class="tit">대여 / 반납시간</div>
										<div class="txt">10:00~21:00 / 10:00~21:00</div>
									</li>
									<li>
										<div class="tit">점심 / 저녁시간</div>
										<div class="txt">12:00~13:00 / 18:00~19:000</div>
									</li>
								</ul>
								<ul>
									<li>
										<div class="tit">장비번호 선택</div>
										<div class="txt">
											<div class="baseSel">
												<select>
													<option value="">일반</option>
													<option value="">일반</option>
													<option value="">일반</option>
												</select>
											</div>
										</div>
									</li>
									<li>
										<div class="tit">대여일/반납일</div>
										<div class="txt">
											<div class="cmsDate">
												<div class="baseInput">
													<input id="st" readonly type="text" title="시작날짜" value="2024-12-16" >
												</div>
												<div class="line">-</div>
												<div class="baseInput">
													<input id="ed" readonly type="text" title="마지막날짜" value="2024-12-31">
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>

							<div class="totalPrice">
								<div class="nameDate">
									HXR-MC88 / 10011  / 2일
								</div>
								<div class="price">30,000원</div>
								<a href="javascript:void(0)" class="close"><img src="/images/ico_smClose.svg" alt="닫기"></a>
							</div>
							<div class="btnOrder mobFix two">
								<a href="cart.php" class="btnType1 lineBlue">장바구니</a>
								<a href="order.php" class="btnType1">대여 신청</a>
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
						<div class="wTit">대여 신청 안내</div>
						<div class="iconList">
							<ul>
								<li>
									<div class="img"><img src="/images/ico_eq6.svg" alt="아이콘"></div>
									<div class="text">
										<div class="step">STEP 01</div>
										<div class="tit">장비번호 선택</div>
									</div>
								</li>
								<li>
									<div class="img"><img src="/images/ico_eq7.svg" alt="아이콘"></div>
									<div class="text">
										<div class="step">STEP 02</div>
										<div class="tit">대여 일자 선택</div>
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
										<div class="tit">대여신청</div>
									</div>
								</li>
							</ul>
						</div>
					</div>

					<div class="whiteBox">
						<div class="wTit">유의사항</div>
						<ul class="textUl">
							<li>· 기자재는 일반회원으로 가입하셔야 대여하실 수 있습니다.</li>
							<li>· 장비의 대여와 반납은 신청자(회원) 본인이 직접 하셔야 합니다.</li>
						</ul>	
					</div>
					<div class="whiteBox">
						<div class="wTit"> 장비대여 주의사항</div>
						<ul class="textUl">
							<li>· 홈페이지를 통해서 최소 2일전에 예약 신청 후, 담당자와 통화 후 예약이 확정됩니다.</li>
							<li> * 담당자와 통화를 하지 않고 방문 시 장비대여 불가 (070-4607-1214, 070-4607-1215)</li>
						</ul>	
						<br />
						<ul class="textUl">
							<li>· 장비 대여료 결제는 계좌이체를 통해서만 가능합니다.<br />(주안영상미디어센터 계좌번호 : 신한은행 100-035-698102)</li>
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