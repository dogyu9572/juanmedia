<?php include("../inc/header.php"); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

$dblink = SetConn($_conf_db["main_db"]);
$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]));

$arrLevel = getArticleList ( $_conf_tbl ["member_level"], 0, 0, "order by level_no desc " );

for($i = 0; $i < $arrLevel["total"]; $i ++) {
    $arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}

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
									<li><a href="freeList.php">보도자료</a></li>
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
									<option value="freeList.php">보도자료</option>
								</select>
							</div>
						</div>

						<div class="stateMember">
							홍길동님의 현재 회원 상태는 <strong><?=$arrayLevel[$arrInfo['list'][0]['user_level']]?></strong> 입니다.
						</div>
						<!-- searchForm -->
						<div class="searchForm one">
							<div class="count">
								전체 <span><?=number_format($arrInfo["total"])?>건</span>
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
<!--                                            <col class="no4" />-->
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>위반내용</th>
                                            <th>정지구분</th>
<!--                                            <th>등록일</th>-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $arrChildViolation = explode("||", $arrInfo["list"][0]['child_violation']);
                                        $arrChildCategory = explode("||", $arrInfo["list"][0]['child_category']);
                                        $arrChildViolationWdate = explode("||", $arrInfo["list"][0]['child_violation_wdate']);

                                        if ($arrInfo["list"][0]['child_violation_wdate']) {
                                            for ($i = 0; $i < count($arrChildViolationWdate); $i++) {
                                                ?>
                                                <tr>
                                                    <td><?= ($i + 1) ?></td>
<!--                                                    <td class="name"><a href="#;" onclick="contentPop('.stopPop');">--><?php //= $arrChildViolation[$i] ?><!--</a></td>-->
                                                    <td class="name"><a href="#;"><?= $arrChildViolation[$i] ?></a></td>
                                                    <td><?= $arrChildCategory[$i] ?></td>
<!--                                                    <td>--><?php //= $arrChildViolationWdate[$i] ?><!--</td>-->
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4">등록된 내역이 없습니다.</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
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
                                echo pageNavigationUser($arrInfo["total"],10,10,$_GET['offset'],$reQueryString);
                                ############### paging ############### ED
                                ?>
                            </div>
                            <!-- //pagingWrap -->

                        </div>
                        <!-- //btnPagingWrap -->


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



