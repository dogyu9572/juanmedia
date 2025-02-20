<?php include("../inc/header.php"); ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/module/member/auth.php";

include_once $_SERVER['DOCUMENT_ROOT']."/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$boardid = "edu_applicants";
$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid );

$arrBoardList	= getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], "", $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'] , "and w_user = '" . $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] . "'");

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
							<div class="tit">신청 내역</div>
							<ul>
								<li><a href="freeList.php">나의 활동 관리</a></li>
								<li><a href="edit.php">나의 정보 관리</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- subSec -->
			<div class="subSec pt80 last">
				<div class="mySide inner ">
					
					<div class="menu">
						<div class="inMenu">
							<div class="box">
								<div class="tit">신청 내역</div>
								<ul>
									<li><a href="orderList.php" class="active">교육신청</a></li>
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
						<div class="bigTit">교육신청</div>

						<div class="mobSelect mob">
							<div class="baseSel">
								<select>
									<option value="orderList.php">교육신청</option>
									<option value="orderListEq.php">장비대여</option>
									<option value="orderListPlace.php">공간대관</option>
									<option value="orderListVideo.php">상영회</option>
								</select>
							</div>
						</div>

						<!-- searchForm -->
						<div class="searchForm">
							<div class="count">
								전체 <span><?=number_format($arrBoardList["total"])?>건</span>
							</div>
                            <form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
							<div class="rightForm">
								<div class="baseSel">
									<select name="sw">
                                        <option value="">선택</option>
                                        <option value='app_no' <?=$_GET["sw"] == "app_no"?"selected":""?>>접수번호</option>
                                        <option value="subject" <?=$_GET["sw"] == "s"?"selected":""?>>교육명</option>
                                        <option value='status' <?=$_GET["sw"] == "status"?"selected":""?>>신청상태</option>
									</select>
								</div>
								<div class="search">
									<div class="baseInput">
                                        <input type="text" name="sk" id="sk" value="<?=$_GET["sk"]?>" class="text" placeholder="검색어를 입력하세요.">
									</div>
                                    <a href="javascript:void(0);" onclick="document.form1.submit();"><img src="/images/ico_search.svg" alt="검색"></a>
								</div>
							</div>
                            </form>
						</div>
						<!-- //searchForm -->

						<div class="tableScroll">
							
							<div class="scroll">
								<div class="tableType2 order">
									<table>
										<colgroup>
											<!-- <col class="num" /> -->
											<col class="w110" />
											<col class="w120" />
											<col class="wauto" />
											<col class="w120" />
											<col class="w130" />
											<col class="w90" />
											<col class="w90" />
											<col class="w120" />
											<col class="w100" />
										</colgroup>
										<thead>
											<tr>
												<th>NO.</th>
												<!-- <th>접수번호</th> -->
												<th>카테고리</th>
												<th>교육명</th>
												<th>교육기간</th>
												<th>교육 요일/시간</th>
												<th>결제금액</th>
												<th>신청일</th>
												<th>신청상태</th>
												<th>상세보기</th>
											</tr>
										</thead>
										<tbody>
                                        <?
                                        if($arrBoardList["list"]["total"] > 0){
                                            for($i=0; $i < $arrBoardList["list"]["total"]; $i++){

                                            //공지
                                            $categoryTitle = $arrBoardList["total"]-$i-(int)$_GET['offset'];
                                            $TrClass="";
                                            $noticeMo="";
                                            if($arrBoardList["list"][$i]['no']=="0"){
                                                $TrClass="class=\"notice\"";	// 공지글 표시
                                                $categoryTitle = '<span class="notiTit">공지</span>';
                                                $noticeMo = '<span class="notiTit">공지</span>';
                                            }

                                            $arrBoardArticle = getBoardArticleView("edu", "", $arrBoardList["list"][$i]['edu_idx'],"list");

                                            $dayTypeMap = [
                                                'weekly' => '매주',
                                                'biweekly' => '격주',
                                                'other' => '기타'
                                            ];
                                            $dayType = $dayTypeMap[$arrBoardArticle["list"][0]['day_type']];

                                            $days = str_replace('|', '/', $arrBoardArticle["list"][0]['days']);

                                        ?>
											<tr data-order="<?=$arrBoardList['list'][$i]['idx']?>">
												<!-- <td><?=$arrBoardList["list"][$i]['no']=="0"?"공지":$categoryTitle?></td> -->
												<td><?=$arrBoardList["list"][$i]['app_no']?></td>
												<td><?=getCategoryName($arrBoardList["list"][$i]['category1']);?>/<?=getCategoryName($arrBoardList["list"][$i]['category2']);?></td>
												<td><a href="/edu/list.php?boardid=edu&mode=view&idx=<?=$arrBoardArticle["list"][0]['idx']?>"><?=$arrBoardList["list"][$i]['subject']?></a></td>
												<td><?=$arrBoardArticle["list"][0]['e_start_date']?> ~<br /><?=$arrBoardArticle["list"][0]['e_end_date']?></td>
												<td>
                                                    <?=$dayType?> <?=$days?>
                                                    <?=$arrBoardArticle["list"][$i]['start_hour']?>:<?=$arrBoardArticle["list"][$i]['start_minute']?> ~ <?=$arrBoardArticle["list"][$i]['end_hour']?>:<?=$arrBoardArticle["list"][$i]['end_minute']?>
                                                </td>
												<td><?= number_format($arrBoardList["list"][$i]['finalamount']) ?>원</td>
												<td><?=date("Y-m-d",strtotime($arrBoardList["list"][$i]['wdate']))?></td>
												<td>
                                                    <?=$arrBoardList["list"][$i]['status'];?>
                                                    <?php if (isset($arrBoardList["list"][$i]['wait_number']) && $arrBoardList["list"][$i]['wait_number'] > 0): ?>
                                                       <br/>(대기 <?=$arrBoardList["list"][$i]['wait_number']?>번)
                                                    <?php endif; ?>
                                                </td>
												<td>
													<a href="detail.php?idx=<?=$arrBoardList["list"][$i]['idx'];?>" class="btnTypeSm">상세보기</a>
												</td>
											</tr>
                                            <?
                                        }
                                        }else{
                                            ?>
                                            <tr height="100">
                                                <td colspan="9">등록된 데이터가 없습니다.</td>
                                            </tr>
                                        <?}?>
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
                            echo pageNavigationUser($arrBoardList["total"],$arrBoardInfo["list"][0]["scale"],$arrBoardInfo["list"][0]["pagescale"],$_GET['offset'],$reQueryString);
                            ############### paging ############### ED
                            ?>
                        </div>
                        <!-- //pagingWrap -->


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



