<?php include("./inc/header.php"); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/banner/banner.lib.php";

$dblink = SetConn($_conf_db["main_db"]);
$arrEduListAll = getBoardListBaseNFile("edu", "", "", "", 4, 0,'', "user"); // 전체 교육 리스트 가져오기
$arrEduList1 = getBoardListBaseNFile("edu", "", "", "", 4, 0,'and reception_status="접수중"', "user"); // 접수중인 교육 리스트 가져오기
$arrEduList2 = getBoardListBaseNFile("edu", "", "", "", 4, 0,'and reception_status="대기접수"', "user"); // 대기접수중인 교육 리스트 가져오기
$arrEduList3 = getBoardListBaseNFile("edu", "", "", "", 4, 0,'and reception_status="교육중"', "user"); // 교육중인 교육 리스트 가져오기
$arrEduList4 = getBoardListBaseNFile("edu", "", "", "", 4, 0,'and reception_status="종료"', "user"); // 종료된 교육 리스트 가져오기
$arrEquList = getBoardListBaseNFile("equ", "", "", "", 3, 0,'', "user"); // 장비 대여 리스트 가져오기
$arrPlaceList = getBoardListBaseNFile("place", "", "", "", 3, 0,'', "user"); // 공간 대관 리스트 가져오기
$arrNoticeList = getBoardListBaseNFile("notice", "", "", "", 4, 0,'', "user"); // 공지사항 리스트 가져오기
$arrYoutubeList = getBoardListBaseNFile("youtube", "", "", "", 2, 0,'', "user"); // 유튜브 리스트 가져오기
$arrPCBannerList = getDeviceBannerList(1,"1"); // PC 배너 리스트 가져오기
$arrMOBannerList = getDeviceBannerList(1,"2"); // 모바일 배너 리스트 가져오기
$arrBottomPCBannerList = getDeviceBannerList(2,"3"); // 하단 PC 배너 리스트 가져오기
$arrBoardHolidayList = getBoardListBaseNFile("holiday", $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $_GET['reply']); // 휴관일 리스트 가져오기
$holidayWeekdays = [];
$specificHolidayDates = [];

foreach ($arrBoardHolidayList['list'] as $holiday) {
    // 요일 정보 처리
    if (!empty($holiday['weekdays'])) {
        $weekdays = explode('|', $holiday['weekdays']);
        $holidayWeekdays = array_merge($holidayWeekdays, $weekdays);
    }

    // 특정 날짜 범위 처리
    if (!empty($holiday['holly_start_date']) && !empty($holiday['holly_end_date'])) {
        $startDate = strtotime($holiday['holly_start_date']);
        $endDate = strtotime($holiday['holly_end_date']);

        for ($date = $startDate; $date <= $endDate; $date = strtotime('+1 day', $date)) {
            $specificHolidayDates[] = date('Y-m-d', $date);
        }
    }
}

// 중복 제거
$holidayWeekdays = array_unique($holidayWeekdays);
$specificHolidayDates = array_unique($specificHolidayDates);

// JavaScript 배열로 변환
$holidayWeekdaysJson = json_encode($holidayWeekdays);
$specificHolidayDatesJson = json_encode($specificHolidayDates);

//DB해제
SetDisConn($dblink);
?>
<script>
    const holidayWeekdaysJson = <?= json_encode($holidayWeekdays) ?>;
    const specificHolidayDatesJson = <?= json_encode($specificHolidayDates) ?>;
</script>
<script src="/js/calendar.js"></script>
<!-- Container -->
<div class="container" id="container">
    <!-- mainSec -->
    <div class="mainSec inner">
        <!-- mainSlide -->
        <div class="mainSlide ">
            <div class="swiper-wrapper">
                <?php
                if (!empty($arrPCBannerList["list"]) || !empty($arrMOBannerList["list"])) {
                    $total = max($arrPCBannerList["list"]["total"], $arrMOBannerList["list"]["total"]);
                    for ($i = 0; $i < $total; $i++) {
                        $pc_image = "/pub/images/mvisual01.png";
                        $mo_image = "/pub/images/mvisual01.png";
                        $target = "";
	                    $link_attr = '';

                        if (!empty($arrPCBannerList["list"][$i]["b_image"])) {
                            $pc_image = "/uploaded/banner/" . $arrPCBannerList["list"][$i]["b_image"];
                        }
                        if (!empty($arrMOBannerList["list"][$i]["b_image"])) {
                            $mo_image = "/uploaded/banner/" . $arrMOBannerList["list"][$i]["b_image"];
                        }

	                    if (!empty($arrPCBannerList["list"][$i]["b_url"]) && $arrPCBannerList["list"][$i]["b_url"] != "https://") {
		                    $link_attr = 'href="'.$arrPCBannerList["list"][$i]["b_url"].'" target="'.$arrPCBannerList["list"][$i]["b_target"].'"';
	                    } elseif (!empty($arrMOBannerList["list"][$i]["b_url"]) && $arrMOBannerList["list"][$i]["b_url"] != "https://") {
		                    $link_attr = 'href="'.$arrMOBannerList["list"][$i]["b_url"].'" target="'.$arrMOBannerList["list"][$i]["b_target"].'"';
	                    }
                        ?>
                        <div class="swiper-slide">
                            <a <?=$link_attr?>>
                                <img src="<?=$pc_image?>" class="pc" alt="슬라이드">
                                <img src="<?=$mo_image?>" class="mob" alt="슬라이드">
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="swiper-slide">
                        <img src="/images/mainSlide1.png" class="pc" alt="슬라이드">
                        <img src="/images/mainSlide1_mob.jpg" class="mob" alt="슬라이드">
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="controler">
                <div class="count"><span class="state">01</span> <span class="bar">/</span> <span class="total"></span>
                </div>
                <div class="swiperNav">
                    <div class="nav prev"></div>
                    <div class="nav next"></div>
                </div>
            </div>
        </div> <!-- //mainSlide -->
        <!-- mainIcon -->
        <div class="mainIcon">
            <ul>
                <li><a href="/edu/list.php"> <span class="img"><img src="/images/ico_main01.svg" alt="교육신청"></span> <span class="tit">교육신청</span> </a></li>
                <li><a href="/equ/list.php"> <span class="img"><img src="/images/ico_main02.svg" alt="장비대여"></span> <span class="tit">장비대여</span> </a></li>
                <li><a href="/place/list.php"> <span class="img"><img src="/images/ico_main03.svg" alt="공간대관"></span> <span class="tit">공간대관</span> </a></li>
                <li><a href="/media/order.php"> <span class="img"><img src="/images/ico_main04.svg" alt="체험신청"></span> <span class="tit">체험신청</span> </a></li>
                <li><a href="/mypage/orderList.php"> <span class="img"><img src="/images/ico_main05.svg" alt="신청확인"></span> <span class="tit">신청확인</span> </a></li>
            </ul>
        </div> <!-- //mainIcon -->
        <!-- mainMedia -->
        <div class="mainMedia">
            <div class="mainTit">
                <div class="tit">미디어 교육</div>
                <div class="tabList">
                    <a href="javascript:void(0)" class="active" data-name="all">전체</a>
                    <a href="javascript:void(0)" data-name="ing">접수중</a>
                    <a href="javascript:void(0)" data-name="etc">대기접수</a>
                    <a href="javascript:void(0)" data-name="ready">교육중</a>
                    <a href="javascript:void(0)" data-name="end">종료</a>
                </div>
                <a href="/edu/list.php" class="btnMore">More</a>
            </div>
            <div class="tabCont">
                <div id="cont" class="all">
                    <ul class="swiper-wrapper">
	                    <?php
	                    for ($i = 0; $i < $arrEduListAll["list"]["total"]; $i++) {
		                    $status = $arrEduListAll["list"][$i]["reception_status"];
		                    $class = "";
		                    $text = "";

		                    switch ($status) {
			                    case "접수중":
				                    $class = "ing";
				                    $text = "접수중";
				                    break;
			                    case "대기접수":
				                    $class = "etc";
				                    $text = "대기접수";
				                    break;
			                    case "교육중":
				                    $class = "ready";
				                    $text = "교육중";
				                    break;
			                    case "종료":
				                    $class = "end";
				                    $text = "종료";
				                    break;
			                    default:
				                    $class = "unknown";
				                    $text = "알수없음";
				                    break;
		                    }
		                    ?>
                            <li class="all">
                                <a href="/edu/list.php?boardid=edu&mode=view&idx=<?=$arrEduListAll["list"][$i]["idx"]?>">
                                    <span class="img"><img src="/uploaded/board/edu/<?=$arrEduListAll["list"][$i]["re_name"]?>" alt="썸네일"></span>
                                    <div class="stateBox <?=$class?>"><span><?=$text?></span></div>
                                </a>
                            </li>
	                    <?php } ?>
                        <?php for($i=0;$i<$arrEduList1["list"]["total"];$i++){?>
                            <li class="ing">
                                <a href="/edu/list.php?boardid=edu&mode=view&idx=<?=$arrEduList1["list"][$i]["idx"]?>">
                                    <span class="img"><img src="/uploaded/board/edu/<?=$arrEduList1["list"][$i]["re_name"]?>" alt="썸네일"></span>
                                    <div class="stateBox ing"><span>접수중</span></div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php for($i=0;$i<$arrEduList2["list"]["total"];$i++){?>
                            <li class="etc">
                                <a href="/edu/list.php?boardid=edu&mode=view&idx=<?=$arrEduList2["list"][$i]["idx"]?>">
                                    <span class="img"><img src="/uploaded/board/edu/<?=$arrEduList2["list"][$i]["re_name"]?>" alt="썸네일"></span>
                                    <div class="stateBox etc"><span><span>대기<br />접수</span</span></div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php for($i=0;$i<$arrEduList3["list"]["total"];$i++){?>
                            <li class="ready">
                                <a href="/edu/list.php?boardid=edu&mode=view&idx=<?=$arrEduList3["list"][$i]["idx"]?>">
                                    <span class="img"><img src="/uploaded/board/edu/<?=$arrEduList3["list"][$i]["re_name"]?>" alt="썸네일"></span>
                                    <div class="stateBox ready"><span><span>교육중</span></span></div>
                                </a>
                            </li>
                        <?php } ?>
                        <?php for($i=0;$i<$arrEduList4["list"]["total"];$i++){?>
                            <li class="end">
                                <a href="/edu/list.php?boardid=edu&mode=view&idx=<?=$arrEduList4["list"][$i]["idx"]?>">
                                    <span class="img"><img src="/uploaded/board/edu/<?=$arrEduList4["list"][$i]["re_name"]?>" alt="썸네일"></span>
                                    <div class="stateBox end"><span>종료</span></div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div> <!-- //mainMedia -->
        <!-- mainSide -->
        <div class="mainSide">
            <!-- mainEq -->
            <div class="mainEq">
                <div class="mainTit">
                    <div class="tit">장비·공간대여</div>
                    <div class="tabList">
                        <a href="javascript:void(0)" class="active" data-name="eq">장비대여</a>
                        <a href="javascript:void(0)" data-name="place">공간대관</a>
                    </div>
                        <a href="/place/list.php" class="btnMore place">More</a>
                        <a href="/equ/list.php" class="btnMore eq">More</a>
                </div>
                <div class="tabCont">
                    <div id="contEq" class="eq">
                        <ul class="swiper-wrapper">
                            <?php for($i=0;$i<$arrEquList["list"]["total"];$i++){ ?>
                                <li class="eq">
                                    <a href="/equ/list.php?boardid=edu&mode=view&idx=<?=$arrEquList["list"][$i]["idx"]?>">
                                        <div class="img">
                                            <img src="/uploaded/board/equ/<?=$arrEquList["list"][$i]["re_name"]?>" alt="썸네일">
                                            <div class="pop">
                                                <div class="popTit">대여하기</div>
                                            </div>
                                        </div>
                                        <div class="box">
                                            <span class="tit"><?=$arrEquList["list"][$i]["subject"]?></span>
                                            <span class="txt"><?=number_format($arrEquList["list"][$i]["fee"])?>원(1일)</span>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div id="contEq" class="place">
                        <ul class="swiper-wrapper">
                            <?php for($i=0;$i<$arrPlaceList["list"]["total"];$i++){ ?>
                                <li class="place">
                                    <a href="/place/list.php?boardid=place&mode=view&idx=<?=$arrPlaceList["list"][$i]["idx"]?>">
                                        <div class="img">
                                            <img src="/uploaded/board/place/<?=$arrPlaceList["list"][$i]["re_name"]?>" alt="썸네일">
                                            <div class="pop">
                                                <div class="popTit">대여하기</div>
                                            </div>
                                        </div>
                                        <div class="box">
                                            <span class="tit"><?=$arrPlaceList["list"][$i]["subject"]?></span>
                                            <span class="txt"><?=number_format($arrPlaceList["list"][$i]["fee"])?>원(1시간)</span>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div> <!-- //mainEq -->
            <!-- mainCal -->
            <div class="mainCal">
                <div class="mainTit">
                    <div class="tit">운영안내</div>
                </div>
                <div class="calendar">
                    <div class="top">
                        <div class="year">
                            <button><img src="/images/ico_calPrev.svg" alt="이전"></button>
                            <span class="date">2024.09</span>
                            <button><img src="/images/ico_calNext.svg" alt="다음"></button>
                        </div>
                        <div class="dayInfo"><span class="today">오늘</span> <span class="holiday">휴관일</span></div>
                    </div>
                    <table class="tableCal">
                        <thead>
							<tr>
								<th>일</th>
								<th>월</th>
								<th>화</th>
								<th>수</th>
								<th>목</th>
								<th>금</th>
								<th>토</th>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td class="holiday"><span>1</span></td>
								<td class="holiday"><span>2</span></td>
								<td><span>3</span></td>
								<td><span>4</span></td>
								<td class="today"><span>5</span></td>
								<td><span>6</span></td>
								<td><span>7</span></td>
							</tr>
							<tr>
								<td class="holiday"><span>8</span></td>
								<td class="holiday"><span>9</span></td>
								<td><span>10</span></td>
								<td><span>11</span></td>
								<td><span>12</span></td>
								<td><span>13</span></td>
								<td><span>14</span></td>
							</tr>
							<tr>
								<td class="holiday"><span>15</span></td>
								<td class="holiday"><span>16</span></td>
								<td><span>17</span></td>
								<td><span>18</span></td>
								<td><span>19</span></td>
								<td><span>20</span></td>
								<td><span>21</span></td>
							</tr>
							<tr>
								<td class="holiday"><span>22</span></td>
								<td class="holiday"><span>23</span></td>
								<td><span>24</span></td>
								<td><span>25</span></td>
								<td><span>26</span></td>
								<td><span>27</span></td>
								<td><span>28</span></td>
							</tr>
							<tr>
								<td class="holiday"><span>29</span></td>
								<td class="holiday"><span>30</span></td>
								<td><span>31</span></td>
								<td><span>1</span></td>
								<td><span>2</span></td>
								<td><span>3</span></td>
								<td><span>4</span></td>
							</tr>
                        </tbody>
                    </table>
                </div>
            </div> <!-- //mainCal -->
        </div> <!-- //mainSide -->
        <!-- mainSide2 -->
        <div class="mainSide2">
            <!-- mainNotice -->
            <div class="mainNotice">
                <div class="mainTit">
                    <div class="tit">공지 사항</div>
                    <a href="/cm/notice.php" class="btnMore">More</a>
                </div>
                <div class="noticeList">
                    <ul>
                        <?php for($i=0;$i<$arrNoticeList["list"]["total"];$i++){?>
                            <li><a href="/cm/notice.php?boardid=notice&mode=view&idx=<?=$arrNoticeList["list"][$i]["idx"]?>"> <span class="tit"><?=$arrNoticeList["list"][$i]["subject"]?></span> <span
                                            class="date"><?=date('Y.m.d', strtotime($arrNoticeList["list"][$i]['wdate']))?></span> </a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div> <!-- //mainNotice -->
            <!-- mainYoutube -->
            <div class="mainYoutube">
                <div class="mainTit">
                    <div class="tit">주영미 유튜브</div>
                </div>
                <div class="youtubeList">
                    <ul>
                        <?php for($i=0;$i<$arrYoutubeList["list"]["total"];$i++){?>
                            <li><a href="<?=$arrYoutubeList["list"][$i]["homepage"]?>" target="_blank"><span class="img"><img src="/uploaded/board/youtube/<?=$arrYoutubeList["list"][$i]["re_name"]?>" alt="썸네일"></span>
                                    <div class="text"><span class="tit"><?=$arrYoutubeList["list"][$i]["subject"]?></span>
                                        <span class="txt"><?=$arrYoutubeList["list"][$i]["contents"]?></span>
                                    </div>
                                </a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div> <!-- //mainYoutube -->
            <!-- mainVideo -->
            <div class="mainVideo">
                <div class="mainTit">
                    <div class="tit">주영미 안내</div>
                    <div class="swiperPaging"></div>
                </div>
                <div class="videoSwiper">
                    <div class="swiper-wrapper">
                        <?php
                        if (!empty($arrBottomPCBannerList["list"]) || !empty($arrBottomMOBannerList["list"])) {
                            $total = max($arrBottomPCBannerList["list"]["total"], $arrBottomMOBannerList["list"]["total"]);
                            for ($i = 0; $i < $total; $i++) {
                                $pc_image = "/pub/images/mvisual01.png";
                                $mo_image = "/pub/images/mvisual01.png";
                                $url = "#this";
                                $target = "";

                                if (!empty($arrBottomPCBannerList["list"][$i]["b_image"])) {
                                    $pc_image = "/uploaded/banner/" . $arrBottomPCBannerList["list"][$i]["b_image"];
                                }
                                if (!empty($arrBottomMOBannerList["list"][$i]["b_image"])) {
                                    $mo_image = "/uploaded/banner/" . $arrBottomMOBannerList["list"][$i]["b_image"];
                                }

                                if (!empty($arrBottomPCBannerList["list"][$i]["b_url"])) {
                                    $url = $arrBottomPCBannerList["list"][$i]["b_url"];
                                    $target = " target='" . $arrBottomPCBannerList["list"][$i]["b_target"] . "' ";
                                } elseif (!empty($arrBottomMOBannerList["list"][$i]["b_url"])) {
                                    $url = $arrBottomMOBannerList["list"][$i]["b_url"];
                                    $target = " target='" . $arrBottomMOBannerList["list"][$i]["b_target"] . "' ";
                                }
                                ?>
                                <div class="swiper-slide"><a href="<?=$url?>" <?=$target?>><img src="<?=$pc_image?>" alt="슬라이드"> </a></div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="swiper-slide"><a href="#;"> <img src="/images/thumb2_1.png" alt="슬라이드"> </a></div>
                            <div class="swiper-slide"><a href="#;"> <img src="/images/thumb2_2.png" alt="슬라이드"> </a></div>
                            <div class="swiper-slide"><a href="#;"> <img src="/images/thumb2_3.png" alt="슬라이드"> </a></div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div> <!-- //mainVideo -->
        </div> <!-- //mainSide2 -->
        <!-- snsIntro --->
        <div class="snsIntro">
            <div class="title">주안영상미디어센터의 <span>SNS를 소개</span>합니다.</div>
            <div class="snsList">
                <ul>
                    <li><a href="https://blog.naver.com/juanmedia1731" target="_blank"> <span class="img"><img
                                        src="/images/ico_sns4.svg" alt="네이버블로그"></span> <span class="tit">네이버블로그</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/login/?next=https%3A%2F%2Fwww.facebook.com%2Fjuan2622%2F%3Flocale%3Dko_KR"
                           target="_blank"> <span class="img"><img src="/images/ico_sns2.svg" alt="페이스북"></span> <span
                                    class="tit">페이스북</span> </a>
                    </li>
                    <li><a href="https://www.instagram.com/yeongsang.juan/" target="_blank"> <span class="img"><img
                                        src="/images/ico_sns7.svg" alt="인스타그램"></span> <span class="tit">인스타그램</span>
                        </a>
                    </li>
                    <li><a href="https://x.com/i/flow/login?redirect_after_login=%2Fjuanyeongsangm1" target="_blank">
                            <span class="img"><img src="/images/ico_sns5.svg" alt="트위터"></span> <span
                                    class="tit">트위터</span> </a></li>
                    <li>
                        <a href="https://www.daangn.com/kr/local-profile/%EC%A3%BC%EC%95%88%EC%98%81%EC%83%81%EB%AF%B8%EB%94%94%EC%96%B4%EC%84%BC%ED%84%B0-%EB%AF%B8%EB%94%94%EC%96%B4-%ED%8C%8C%ED%81%AC-%EB%8F%99%EB%84%A4%EC%97%85%EC%B2%B4-%EC%A3%BC%EC%95%88%EC%98%81%EC%83%81%EB%AF%B8%EB%94%94%EC%96%B4%EC%84%BC%ED%84%B0-%EB%AF%B8%EB%94%94%EC%96%B4-%ED%8C%8C%ED%81%AC-2068506/?in=%EC%9A%A9%EC%82%B0%EA%B5%AC-36"
                           target="_blank"> <span class="img"><img src="/images/ico_sns6.svg" alt="당근마켓"></span> <span
                                    class="tit">당근마켓</span> </a>
                    </li>
                    <li><a href="http://pf.kakao.com/_xcvsdG/friend" target="_blank"> <span class="img"><img
                                        src="/images/ico_sns3.svg" alt="카카오톡"></span> <span class="tit">카카오톡 채널 추가</span>
                        </a></li>
                    <li><a href="http://imediaschool.kr/" target="_blank"> <span class="img"><img
                                        src="/images/ico_sns1.svg" alt="아이미디어스쿨"></span> <span class="tit">아이미디어스쿨</span>
                        </a></li>
                </ul>
            </div>
        </div> <!-- //snsIntro --->
    </div> <!-- //mainSec -->
</div> <!-- //Container -->
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/module/popup/popup.inc.php";
?>
<?php include("./inc/quick.php"); ?>
<?php include("./inc/footer.php"); ?>
</div> <!-- //Wrap -->
</body>

</html>