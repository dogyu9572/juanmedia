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

			<!-- pageTitle -->
			<div class="pageTitle inner">공간대관 신청</div>
			<!-- //pageTitle -->

			<!-- subSec -->
			<div class="subSec pt0 last">
				<div class="inner">
					<!-- orderSide -->
					<div class="orderSide">
						<!-- detailWrap -->
						<div class="detailWrap">
							<div class="detailTit">공간 정보</div>
							<div class="simpleView">
								<div class="simpleBox">
									<div class="img"><img src="/images/thumb9.png" alt="섬네일"></div>
									<div class="textWrap">
										<div class="title">오디오 스튜디오</div>
										<div class="info">
											<div class="tit">대관일</div>
											<div class="txt">2024.12.17 </div>
										</div>
										<div class="info">
											<div class="tit">대관 시간</div>
											<div class="txt">10:00 ~ 13:00</div>
										</div>
										<div class="info">
											<div class="tit">대관금액</div>
											<div class="txt">30,000원</div>
										</div>
									</div>
								</div>
							</div>

							<div class="detailTit">예약자 정보</div>

							<!-- formBox -->
							<div class="formBox mb1">
								<div class="row">
									<div class="formTit">이름</div>
									<div class="right">
										<div class="baseInput">
											<input type="text" value="홍길동" disabled>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">휴대폰번호</div>
									<div class="right">
										<div class="baseInput">
											<input type="text" value="010-0000-0000" disabled>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">이메일주소</div>
									<div class="right">
										<div class="baseInput">
											<input type="text" value="test@test.com" disabled>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">생년월일</div>
									<div class="right">
										<div class="baseInput">
											<input type="text" class="datepicker">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">할인적용</div>
									<div class="right">
										<div class="baseSel">
											<select>
												<option value="">일반</option>
												<option value="">일반</option>
												<option value="">일반</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">증명서 제출</div>
									<div class="right">
										<div class="baseSel">
											<select>
												<option value="">온라인 제출</option>
												<option value="">온라인 제출</option>
												<option value="">온라인 제출</option>
											</select>
										</div>
										<div class="fileAddWrap">
											<div class="inputFile">
												<div class="fileInput">
													<button class="fileInputButton">파일 선택</button>
													<input type="file" class="fileInputHidden" onchange="javascript: document.getElementById('fileName').value = this.value">
												</div>
												<input type="text" id="fileName" class="fileInputTextbox" readonly="readonly" value="">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">사용인원</div>
									<div class="right">
										<div class="baseSel">
											<select>
												<option value="">일반</option>
												<option value="">일반</option>
												<option value="">일반</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">사용목적</div>
									<div class="right">
										<div class="baseInput">
											<input type="text">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">비고 </div>
									<div class="right">
										<div class="baseInput">
											<textarea name="" id="" cols="30" rows="10" class="text w100p" placeholder="내용을 입력해주세요."></textarea>
										</div>
									</div>
								</div>
							</div>
							<!-- //formBox -->

							<!-- <div class="detailTit">결제수단 선택</div> -->
							<!-- formBox -->
							<!-- <div class="formBox">
								<div class="row">
									<div class="formTit">결제수단</div>
									<div class="right">
										<div class="radioList">
											<div class="baseRadio">
												<input type="radio" name="pay" id="radio1" />
												<label for="radio1">무통장 입금</label>
											</div>
											<div class="baseRadio">
												<input type="radio" name="pay" id="radio2" />
												<label for="radio2">실시간 계좌이체</label>
											</div>
										</div>
									</div>
								</div>
							</div> -->
							<!-- //formBox -->
						</div>
						<!-- //detailWrap -->

						<!-- payWrap -->
						<div class="payWrap">
							<div class="detailTit">결제 정보</div>
							<div class="info">
								<div class="tit">총 대관금액</div>
								<div class="txt">30,000원</div>
							</div>
							<div class="info">
								<div class="tit">할인금액</div>
								<div class="txt">0원</div>
							</div>
							<div class="info last">
								<div class="tit">최종금액</div>
								<div class="txt">30,000원</div>
							</div>
							<a href="complete.php" class="btnType1">대관 신청</a>
						</div>
						<!-- //payWrap -->
					</div>
					<!-- //orderSide -->
								 
				</div>
			</div>
			<!-- //subSec -->

		</div>
		<!-- //Container -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.svg",
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
});
//]]>
</script>

</body>
</html>