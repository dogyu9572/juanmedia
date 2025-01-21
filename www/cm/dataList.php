<?php include("../inc/header.php"); ?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg notice">
				<div class="inner">
					<div class="enName">NOTICE BOARD</div>
					<div class="korName">게시판</div>
					<div class="lnb">
						<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
						<div class="lnbSub">
							<div class="tit">게시판</div>
							<ul>
								<li><a href="/edu/info.php">미디어교육</a></li>
								<li><a href="/equ/info.php">장비대여</a></li>
								<li><a href="/place/info.php">공간대관</a></li>
								<li><a href="/media/info.php">미디어체험</a></li>
								<li><a href="/center/intro.php">센터안내</a></li>
							</ul>
						</div>
						<div class="lnbSub">
							<div class="tit">자료실</div>
							<ul>
								<li><a href="/cm/notice.php">공지&뉴스</a></li>
								<li><a href="/cm/dataList.php">자유게시판</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->


			<!-- pageTitle -->
			<div class="pageTitle inner">자료실</div>
			<!-- //pageTitle -->

			<!-- subSec -->
			<div class="subSec pt0 last">
				<div class="inner">
					
					<!-- searchForm -->
					<div class="searchForm">
						<div class="count">
							전체 <span>100건</span>
						</div>
						<div class="rightForm">
							<div class="baseSel">
								<select>
									<option value="">전체</option>
									<option value="">전체</option>
									<option value="">전체</option>
								</select>
							</div>
							<div class="search">
								<div class="baseInput">
									<input type="text">
								</div>
								<a href="#;"><img src="/images/ico_search.svg" alt="검색"></a>
							</div>
						</div>
					</div>
					<!-- //searchForm -->

					<!-- noticeTable -->
					<div class="noticeTable">
						<ul class="thead">
							<li class="no1">NO</li>
							<li class="no2">제목</li>
							<li class="no3">첨부파일</li>
							<li class="no4">작성자</li>
							<li class="no5">조회수</li>
							<li class="no6">등록일</li>
						</ul>
						<div class="tbody">
							<ul>
								<li class="no1">
									120
								</li>
								<li class="no2">
									<div class="titleFile">
										<div class="title"><a href="detailData.php">2024년 영화공간주안 직원(매니저) 채용 공고</a></div>
										<div class="mob">
											<a href="#;" class="ico_file">첨부파일</a>
										</div>
									</div>
									<div class="info mob">
										<div class="box">
											<div class="tit">작성자</div>
											<div class="text">홍*동</div>
										</div>
										<div class="box">
											<div class="tit">등록일</div>
											<div class="text">YYYY.MM.DD</div>
										</div>
									</div>
								</li>
								<li class="no3">
									<a href="#;" class="ico_file">첨부파일</a>
								</li>
								<li class="no4">
									홍*동
								</li>
								<li class="no5">
									999
								</li>
								<li class="no6">
									2024.11.13
								</li>
							</ul>
							<ul>
								<li class="no1">
									120
								</li>
								<li class="no2">
									<div class="titleFile">
										<div class="title"><a href="detailData.php">2024년 영화공간주안 직원(매니저) 채용 공고</a></div>
										<div class="mob">
											<a href="#;" class="ico_file">첨부파일</a>
										</div>
									</div>
									<div class="info mob">
										<div class="box">
											<div class="tit">작성자</div>
											<div class="text">홍*동</div>
										</div>
										<div class="box">
											<div class="tit">등록일</div>
											<div class="text">YYYY.MM.DD</div>
										</div>
									</div>
								</li>
								<li class="no3">
									<a href="#;" class="ico_file">첨부파일</a>
								</li>
								<li class="no4">
									홍*동
								</li>
								<li class="no5">
									999
								</li>
								<li class="no6">
									2024.11.13
								</li>
							</ul>
						</div>
					</div>
					<!-- //noticeTable -->

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
			<!-- //subSec -->

		</div>
		<!-- //Container -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


</body>
</html>



