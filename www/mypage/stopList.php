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
							<div class="tit">나의 활동 관리</div>
							<ul>
								<li><a href="orderList.php">신청 내역</a></li>
								<li><a href="edit.php">나의 정보 관리</a></li>
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
									<li><a href="freeList.php">자유게시판</a></li>
									<li><a href="stopList.php" class="active">자격 정지 내역</a></li>
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
						<div class="bigTit">자격 정지 내역</div>

						<div class="mobSelect mob">
							<div class="baseSel">
								<select>
									<option value="stopList.php">자격 정지 내역</option>
									<option value="freeList.php">자유게시판</option>
								</select>
							</div>
						</div>

						<div class="stateMember">
							홍길동님의 현재 회원 상태는 <strong>정상</strong> 입니다.
						</div>

						<!-- searchForm -->
						<div class="searchForm one">
							<div class="count">
								전체 <span>100건</span>
							</div>
						</div>
						<!-- //searchForm -->

						<div class="tableScroll">
							
							<div class="scroll">
								<div class="tableType2 stop">
									<table>
										<colgroup>
											<col class="no1" />
											<col class="no2" />
											<col class="no3" />
											<col class="no4" />
										</colgroup>
										<thead>
											<tr>
												<th>NO.</th>
												<th>위반내용</th>
												<th>정지구분</th>
												<th>등록일</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>123</td>
												<td class="name"><a href="#;" onclick="contentPop('.stopPop');">텍스트가 표시됩니다. 텍스트가 표시됩니다.</a></td>
												<td>텍스트가 표시됩니다.</td>
												<td>2024.08.28</td>
											</tr>
											<tr>
												<td>123</td>
												<td class="name"><a href="#;" onclick="contentPop('.stopPop');">텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다.</a></td>
												<td>텍스트가 표시됩니다.</td>
												<td>2024.08.28</td>
											</tr>
											<tr>
												<td>123</td>
												<td class="name"><a href="#;" onclick="contentPop('.stopPop');">텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다.</a></td>
												<td>텍스트가 표시됩니다.</td>
												<td>2024.08.28</td>
											</tr>
											<tr>
												<td>123</td>
												<td class="name"><a href="#;" onclick="contentPop('.stopPop');">텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다. 텍스트가 표시됩니다.</a></td>
												<td>텍스트가 표시됩니다.</td>
												<td>2024.08.28</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="infoPop">
								<div class="in">
									<div>
										<div class="ico">
											<span class="arrow prev"><img src="/images/ico_hand1.svg" alt="좌"></span>
											<span class="hand"><img src="/images/ico_hand.svg" alt="손"></span>
											<span class="arrow next"><img src="/images/ico_hand2.svg" alt="우"></span>
										</div>
										<p>좌우로 스크롤 하셔서<br />내용을 확인해주세요.</p>
									</div>
								</div>
							</div>

						</div>

					
						<!-- pagingWrap -->
						<div class="pagingWrap">
							<a href="#;"><img src="/images/ico_paging1.svg" alt="처음"></a>
							<a href="#;"><img src="/images/ico_paging2.svg" alt="이전"></a>
							<div class="num">
								<a href="#;" class="active">1</a>
								<a href="#;">2</a>
								<a href="#;">3</a>
								<a href="#;">4</a>
								<a href="#;">5</a>
							</div>
							<a href="#;"><img src="/images/ico_paging3.svg" alt="다음"></a>
							<a href="#;"><img src="/images/ico_paging4.svg" alt="마지막"></a>
						</div>
						<!-- //pagingWrap -->


					</div>


				</div>
			</div>
			<!-- //subSec -->

		</div>
		<!-- //Container -->


<!-- 컨텐츠팝업 -->
<div class="contentPop stopPop">
	<div class="bg"></div>
	<div class="popIn">
		<div class="content">
			<div class="tableType1 borderTd">
				<table>
					<colgroup>
						<col class="no1" />
						<col class="no2" />
						<col class="no3" />
						<col class="no4" />
					</colgroup>
					<thead>
						<tr>
							<th>No</th>
							<th>위반내용</th>
							<th>정지구분</th>
							<th>등록일</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>위반내용 들어갑니다.</td>
							<td>정지구분</td>
							<td>등록일</td>
						</tr>
						<tr>
							<td>2</td>
							<td>위반내용 들어갑니다.</td>
							<td>정지구분</td>
							<td>등록일</td>
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



