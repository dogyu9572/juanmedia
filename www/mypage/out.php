<?php include("../inc/header.php"); ?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg myPage">
				<div class="inner">
					<div class="enName">MY PAGE</div>
					<div class="korName">마이페이지</div>
					<div class="lnb">
						<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
						<div class="lnbSub">
							<div class="tit">마이페이지</div>
							<ul>
								<li><a href="/edu/info.php">미디어교육</a></li>
								<li><a href="/equ/info.php">장비대여</a></li>
								<li><a href="/place/info.php">공간대관</a></li>
								<li><a href="/media/info.php">미디어체험</a></li>
								<li><a href="/center/intro.php">센터안내</a></li>
								<li><a href="/cm/notice.php">게시판</a></li>
							</ul>
						</div>
						<div class="lnbSub">
							<div class="tit">나의 정보 관리</div>
							<ul>
								<li><a href="orderList.php">신청 내역</a></li>
								<li><a href="freeList.php">나의 활동 관리</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- subSec -->
			<div class="subSec pt80 last">
				<div class="mySide inner">
					
					<div class="menu">
						<div class="inMenu">
							<div class="box">
								<div class="tit">신청 내역</div>
								<ul>
									<li><a href="orderList.php">교육신청</a></li>
									<li><a href="orderListEq.php">장비대여</a></li>
									<li><a href="orderListPlace.php">공간대여</a></li>
									<li><a href="orderListVideo.php">상영회</a></li>
								</ul>
							</div>
							<div class="box">
								<div class="tit">나의 활동 관리</div>
								<ul>
									<li><a href="freeList.php">보도자료</a></li>
									<li><a href="stopList.php">자격 정지 내역</a></li>
								</ul>
							</div>
							<div class="box">
								<div class="tit">나의 정보 관리</div>
								<ul>
									<li><a href="edit.php">회원정보 수정</a></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="rightCont">
						<div class="bigTit">회원탈퇴</div>


						<div class="memberConfirm">
							<p class="no1">회원 비밀번호 확인</p>
							<p class="no2">비밀번호를 한번더 이력해주세요.<br />비밀번호를 입력하시면 회원탈퇴가 완료됩니다.</p>

							<div class="detailWrap">
								<div class="formBox mb1 ">
									<div class="row">
										<div class="formTit">비밀번호</div>
										<div class="right">
											<div class="baseInput">
												<input type="password" placeholder="비밀번호를 입력하세요.">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="btnCenter">
							<a href="#;" class="btnType1 black list">확인</a>
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



