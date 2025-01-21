<?php include("../inc/header.php"); ?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- pageTitle -->
			<div class="pageTitle inner only mob1">장비대여 장바구니</div>
			<!-- //pageTitle -->

			<!-- subSec -->
			<div class="subSec pt0 last">
				<div class="inner">
					
					<div class="btnRight fixed">
						<a href="javascript:void(0)" class="btnType2">선택 삭제</a>
					</div>

					<div class="formTable">
						<ul class="thead">
							<li class="no1">
								<div class="baseCheck">
									<input type="checkbox" id="agreeAll" />
									<label for="agreeAll"></label>
								</div>
							</li>
							<li class="no2">장비정보</li>
							<li class="no3">장비대여 금액</li>
							<li class="no4">대여일수</li>
							<li class="no5">총 대여금액</li>
							<li class="no6">선택</li>
						</ul>
						<div class="tbody agreeCheck">
							<ul>
								<li class="no1">
									<div class="baseCheck">
										<input type="checkbox" id="ck1" />
										<label for="ck1"></label>
									</div>
								</li>
								<li class="li no2">
									<div class="boxImgDetail">
										<div class="img"><img src="/images/thumb1.png" alt="썸네일"></div>
										<div class="boxDetail">
											<div class="name">HXR-MC88</div>
											<div class="info">
												<span class="tit">장비번호</span>
												<span class="txt">10011</span>
											</div>
											<div class="info">
												<span class="tit">대여일/반납일</span>
												<span class="txt">2024-08-21 ~ <br class="mob" />2024-08-28</span>
											</div>
											<div class="info mob">
												<span class="tit">장비대여 금액</span>
												<span class="txt">15,000원(1일)</span>
											</div>
											<div class="info mob">
												<span class="tit">대여일수</span>
												<span class="txt">2일</span>
											</div>
											<div class="info mob">
												<span class="tit">총 대여금액</span>
												<span class="txt">30,000원</span>
											</div>
										</div>
									</div>
								</li>
								<li class="no3">15,000원(1일)</li>
								<li class="no4">2일</li>
								<li class="no5">30,000원</li>
								<li class="no6"><a href="javascript:void(0)" class="btnType2">삭제</a></li>
							</ul>
							<ul>
								<li class="no1">
									<div class="baseCheck">
										<input type="checkbox" id="ck2" />
										<label for="ck2"></label>
									</div>
								</li>
								<li class="li no2">
									<div class="boxImgDetail">
										<div class="img"><img src="/images/thumb1.png" alt="썸네일"></div>
										<div class="boxDetail">
											<div class="name">HXR-MC88</div>
											<div class="info">
												<span class="tit">장비번호</span>
												<span class="txt">10011</span>
											</div>
											<div class="info">
												<span class="tit">대여일/반납일</span>
												<span class="txt">2024-08-21 ~ <br class="mob" />2024-08-28</span>
											</div>
											<div class="info mob">
												<span class="tit">장비대여 금액</span>
												<span class="txt">15,000원(1일)</span>
											</div>
											<div class="info mob">
												<span class="tit">대여일수</span>
												<span class="txt">2일</span>
											</div>
											<div class="info mob">
												<span class="tit">총 대여금액</span>
												<span class="txt">30,000원</span>
											</div>
										</div>
									</div>
								</li>
								<li class="no3">15,000원(1일)</li>
								<li class="no4">2일</li>
								<li class="no5">30,000원</li>
								<li class="no6"><a href="javascript:void(0)" class="btnType2">삭제</a></li>
							</ul>
						</div>
					</div>

					<div class="totalPriceBox">
						<span>총 결제금액</span>
						<strong>30,000원</strong>
					</div>

					<div class="btnCenter mobFix">
						<a href="order.php" class="btnType1 w1">대여 신청</a>
					</div>

					<div class="cartInfo">
						<div class="box">
							<div class="ico no1"><img src="/images/ico_eq10.svg" alt="장바구니 안내"></div>
							<div class="text">
								<div class="tit">장바구니 안내</div>
								<div class="txt">· 장바구니에는 100개까지 상품을 담을 수 있고 최대 90일까지 보관됩니다.</div>
								<div class="txt">· 품절 상품은 별도 표기 됩니다.</div>
							</div>
						</div>
						<div class="box">
							<div class="ico no2"><img src="/images/ico_eq11.svg" alt="할인 안내"></div>
							<div class="text">
								<div class="tit">할인 안내</div>
								<div class="txt">· 할인 혜택은 대여 신청 시 선택할 수 있습니다.</div>
								<div class="txt">· 대여 신청을 클릭 후 예약자 정보에서 할인 옵션을 확인하고 적용할 수 있습니다.</div>
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



