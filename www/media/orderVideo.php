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

			<!-- pageTitle -->
			<div class="pageTitle inner">상영회신청</div>
			<!-- //pageTitle -->

			<!-- subSec -->
			<div class="subSec last pt0	">
				<div class="inner">
					<!-- orderSide -->
					<div class="orderSide video">
						<!-- detailWrap -->
						<div class="detailWrap">
							<div class="detailTit">상영회 정보</div>
							<div class="simpleView">
								<div class="simpleBox">
									<div class="img"><img src="/images/thumb4.png" alt="섬네일"></div>
									<div class="textWrap">
										<div class="title">슈팅걸스</div>
										<div class="info">
											<div class="tit">상영일</div>
											<div class="txt">2020-11-28</div>
										</div>
										<div class="info">
											<div class="tit">상영시간</div>
											<div class="txt">14:00 ~ 15:40</div>
										</div>
										<div class="info">
											<div class="tit">대상</div>
											<div class="txt">선착순무료(45석)</div>
										</div>
									</div>
								</div>
							</div>
							<div class="detailTit">예약자 정보</div>

							<!-- formBox -->
							<div class="formBox mb1">
								<div class="row">
									<div class="formTit">예약자 정보</div>
									<div class="right">
										<div class="baseInput">
											<input type="text">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">휴대폰번호</div>
									<div class="right">
										<div class="baseInput">
											<input type="text">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit">이메일주소</div>
									<div class="right">
										<div class="baseInput">
											<input type="text">
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
									<div class="formTit">비고 </div>
									<div class="right">
										<div class="baseInput">
											<textarea name="" id="" cols="30" rows="10" class="text w100p" placeholder="내용을 입력해주세요."></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="formTit vt">신청경로</div>
									<div class="right">
										<div class="ckList">
											<div class="baseCheck">
												<input type="checkbox" id="ck1" />
												<label for="ck1">홈페이지</label>
											</div>
											<div class="baseCheck long">
												<input type="checkbox" id="ck2" />
												<label for="ck2">블로그</label>
											</div>
											<div class="baseCheck">
												<input type="checkbox" id="ck3" />
												<label for="ck3">페이스북</label>
											</div>
											<div class="baseCheck last">
												<input type="checkbox" id="ck4" />
												<label for="ck4">인스타그램</label>
											</div>
											<div class="baseCheck">
												<input type="checkbox" id="ck5" />
												<label for="ck5">당근마켓</label>
											</div>
											<div class="baseCheck long">
												<input type="checkbox" id="ck6" />
												<label for="ck6">카카오톡 채널</label>
											</div>
											<div class="baseCheck">
												<input type="checkbox" id="ck7" />
												<label for="ck7">문자</label>
											</div>
											<div class="baseCheck">
												<input type="checkbox" id="ck8" />
												<label for="ck8">이메일</label>
											</div>
											<div class="etcWrap">
												<div class="baseCheck">
													<input type="checkbox" id="ck9" />
													<label for="ck9">기타</label>
												</div>
												<div class="baseInput">
													<input type="text">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- //formBox -->
						</div>
						<!-- //detailWrap -->

						<!-- payWrap -->
						<div class="payWrap">
							<div class="detailTit">예약 정보</div>
							<div class="info">
								<div class="tit">상영일</div>
								<div class="txt">2020-11-28</div>
							</div>
							<div class="info">
								<div class="tit">상영시간</div>
								<div class="txt">14:00 ~ 15:40</div>
							</div>
							<div class="info">
								<div class="tit">인원</div>
								<div class="txt">2인</div>
							</div>
							<a href="complete.php" class="btnType1">신청하기</a>
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