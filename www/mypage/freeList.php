<?php include("../inc/header.php"); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
$dblink = SetConn($_conf_db["main_db"]);
$arrBoardList	= getBoardListBaseNFile("free", "", $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'] , "and A.w_user = '" . $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] . "'");

?>

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
									<li><a href="freeList.php" class="active">보도자료</a></li>
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
						<div class="bigTit">보도자료</div>

						<div class="mobSelect mob">
							<div class="baseSel">
								<select>
									<option value="freeList.php">보도자료</option>
									<option value="stopList.php">자격 정지 내역</option>
								</select>
							</div>
						</div>

					<!--	<div class="stateMember">
							홍길동님의 현재 회원 상태는 <strong>정상</strong> 입니다.
						</div>-->

						<!-- searchForm -->
						<div class="searchForm one">
							<div class="count">
								전체 <span><?=number_format($arrBoardList["total"])?>건</span>
							</div>
						</div>
						<!-- //searchForm -->

						<div class="tableScroll">
							
							<div class="scroll">
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
                                        <?
                                        if($arrBoardList["list"]["total"] > 0){
                                            for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
                                                //순번 & 공지 & 신규표시
                                                $listNum = $arrBoardList["total"]-$i-$offset;
                                                //공지
                                                if($arrBoardList["list"][$i]['no']=="0"){
                                                    $categoryTitle = 'class="notice"';
                                                    $listNum = '<span class="tag noti">공지</span>';
                                                }

                                                $arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'], "list");

                                                $fileLinks = '';
                                                $arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'], "list");
                                                for($j=0;$j<$arrBoardArticle["total_files"];$j++){
                                                    if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
//                                $fileLinks = '<a href="javascript:void(0);" class="ico_file" onclick="fileDownload(\'' . $arrBoardArticle["files"][$j]['boardid'] . '\', \'' . $arrBoardArticle["files"][$j]['b_idx'] . '\', \'' . $arrBoardArticle["files"][$j]['idx'] . '\');">' . $arrBoardArticle["files"][$j]['ori_name'] . '</a>';
                                                        $fileLinks = '<a href="javascript:void(0);" class="ico_file" >' . $arrBoardArticle["files"][$j]['ori_name'] . '</a>';
                                                    }
                                                }
                                                ?>
                                                <ul>
                                                    <li class="no1">
                                                        <?=$listNum?>
                                                    </li>
                                                    <li class="no2">
                                                        <div class="titleFile">
                                                            <div class="title"><a href="/cm/free.php?boardid=free&mode=view&idx=<?=$arrBoardList["list"][$i]['idx']?>" class="row <?=$addClass?>"><?=$arrBoardList["list"][$i]['subject']?></a></div>
<!--                                                            <div class="title"><a href="javascript:void(0);" onclick="contentPop('.pop_password');" class="btn_lock row --><?php //=$addClass?><!--" data-idx="--><?php //=$arrBoardList["list"][$i]['idx']?><!--">--><?php //=$arrBoardList["list"][$i]['subject']?><!--</a></div>-->
                                                            <div class="mob">
                                                                <?=$fileLinks?>
                                                            </div>
                                                        </div>
                                                        <div class="info mob">
                                                            <div class="box">
                                                                <div class="tit">작성자</div>
                                                                <div class="text"><?=$arrBoardList["list"][$i]['name']?></div>
                                                            </div>
                                                            <div class="box">
                                                                <div class="tit">등록일</div>
                                                                <div class="text"><?=$arrBoardList["list"][$i]['subject']?></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="no3">
                                                        <?=$fileLinks?>
                                                    </li>
                                                    <li class="no4">
                                                        <?=$arrBoardList["list"][$i]['name']?>
                                                    </li>
                                                    <li class="no5">
                                                        <?=$arrBoardList["list"][$i]['hit']?>
                                                    </li>
                                                    <li class="no6">
                                                        <?=date('Y.m.d', strtotime($arrBoardList["list"][$i]['wdate']))?>
                                                    </li>
                                                </ul>
                                                <?
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <!-- //noticeTable -->

                                <!-- btnPagingWrap -->
                                <div class="btnPagingWrap">
                                    <!-- pagingWrap -->
                                    <div class="pagingWrap">
                                        <?
                                        ############### paging ############### ST
                                        $queryString = explode("&",$_SERVER['QUERY_STRING']);
                                        $reQueryString = "";
                                        $comma = "";
                                        for($i=0;$i<count($queryString);$i++){
                                            if(strpos($queryString[$i],"offset=")===false){
                                                $reQueryString .= $comma.$queryString[$i];
                                                $comma = "&";
                                            }
                                        }
                                        echo pageNavigationUser($arrBoardList["total"],10,10,$_GET['offset'],$reQueryString);
                                        ############### paging ############### ED
                                        ?>
                                    </div>
                                    <!-- //pagingWrap -->

                                    <!--<div class="btn">
                                        <a href="write.php" class="btnWrite"><span>글쓰기</span></a>
                                    </div>-->
                                </div>
                                <!-- //btnPagingWrap -->

                            </div>
                        </div>
                        <!-- //subSec -->

                        <div class="contentPop paymentPop pop_password" style="display:none;">
                            <div class="bg"></div>
                            <div class="popIn">
                                <div class="content">
                                    <div class="popTit">비밀번호 입력</div>
                                    <div class="cancelBox">
                                        <dl>
                                            <dt>비밀번호</dt>
                                            <dd><input type="password" name="password"></dd>
                                        </dl>
                                    </div>
                                    <div class="btnCenter">
                                        <a href="javascript:void(0);" class="btnType1 black list" onclick="validatePassword()">확인</a>
                                    </div>
                                    <div class="closePop">
                                        <a href="javascript:void(0);" onclick="$('.pop_password').hide()">팝업닫기</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('.btn_lock').click(function() {
                                    var idx = $(this).data('idx');
                                    console.log(idx); // Check if idx is correctly retrieved
                                    $('.pop_password').data('idx', idx).show();
                                });
                            });

                            function validatePassword() {
                                var password = $('input[name="password"]').val();
                                var idx = $('.pop_password').data('idx');
                                var boardid = '<?=$arrBoardInfo["list"][0]["boardid"]?>';

                                $.ajax({
                                    type: 'POST',
                                    url: '/module/board/ajax_board_password.php',
                                    data: { password: password, idx: idx, boardid: boardid },
                                    success: function(response) {
                                        if (response.trim() === "true") {
                                            window.location.href = '<?=$_SERVER["PHP_SELF"]?>?boardid=' + boardid + '&mode=view&idx=' + idx;
                                        } else {
                                            alert('비밀번호가 틀렸습니다.');
                                            $('.pop_password').hide();
                                            $('input[name="password"]').val('');
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('AJAX Error: ' + status + error);
                                    }
                                });
                            }
                        </script>
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



