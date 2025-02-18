<?php include("../inc/header.php"); ?>
<?php $gNum = "06"; $sNum = "06"; $gName = "센터안내"; $sName = "센터일정"; ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
$dblink = SetConn($_conf_db["main_db"]);

if ($_GET['boardid'] == '') {
    $_GET['boardid'] = 'all';
}

if($_GET['boardid'] == 'all') {
    // 전체 아이템 수 계산을 위해 먼저 모든 데이터 가져오기
    $allEduList = getBoardListBaseNFile("edu", $_GET["category"], $_GET['sw'], $_GET['sk'], 99999, 0, $_GET['reply']);
    $allVideoList = getBoardListBaseNFile("video", $_GET["category"], $_GET['sw'], $_GET['sk'], 99999, 0, $_GET['reply']);

    // numeric key만 추출
    $eduItems = array_filter($allEduList["list"], 'is_numeric', ARRAY_FILTER_USE_KEY);
    $videoItems = array_filter($allVideoList["list"], 'is_numeric', ARRAY_FILTER_USE_KEY);

    // 타입 추가
    foreach($eduItems as &$item) $item['type'] = 'edu';
    foreach($videoItems as &$item) $item['type'] = 'video';

	// 전체 리스트 합치기 및 정렬
	if (!empty($eduItems) && !empty($videoItems)) {
		$allItems = array_merge($eduItems, $videoItems);
	} elseif (!empty($eduItems)) {
		$allItems = $eduItems;
	} elseif (!empty($videoItems)) {
		$allItems = $videoItems;
	} else {
		$allItems = array();
	}

    usort($allItems, function($a, $b) {
        return strtotime($b['wdate']) - strtotime($a['wdate']);
    });

    // 전체 아이템 수
    $totalItems = count($allItems);

    // 현재 페이지용 아이템 추출
    $combinedList = array_slice($allItems, $offset, $scale);
} else {
    // 기존 개별 게시판 리스트 가져오기
    $arrBoardEduList = getBoardListBaseNFile("edu", $_GET["category"], $_GET['sw'], $_GET['sk'], $scale, $offset, $_GET['reply']);
    $arrBoardVideoList = getBoardListBaseNFile("video", $_GET["category"], $_GET['sw'], $_GET['sk'], $scale, $offset, $_GET['reply']);
}

if ($_GET['boardid'] == 'all') {
    $totalCount = $totalItems;
} elseif ($_GET['boardid'] == 'edu') {
    $totalCount = $arrBoardEduList["list"]["total"] ;
} elseif ($_GET['boardid'] == 'video') {
    $totalCount = $arrBoardVideoList['list']['total'];
}

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
<script src="/js/calendar_sub.js"></script>
		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg center">
				<div class="inner">
					<div class="enName">CENTER INFORMATION</div>
					<div class="korName">센터안내</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- pageTitle -->
			<div class="pageTitle inner">센터일정</div>
			<!-- //pageTitle -->

			<div class="expCal">
				<div class="inner">
					<div class="top">
						<div class="state">
							<span>가능</span>
							<span class="not">불가능</span>
						</div>
						<div class="year">
							<a href="#;"><img src="/images/ico_calPrev.svg" alt="이전"></a>
							<span class="num">2024.10</span>
							<a href="#;"><img src="/images/ico_calNext.svg" alt="다음"></a>
						</div>
					</div>

					<div id="scrollBar">
						<div class="contScroll">
							 <ul>
							 	<li>
									<a href="#;">
										<span class="week">휴일</span>
										<span class="day">1</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">수</span>
										<span class="day">2</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">휴일</span>
										<span class="day">3</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">금</span>
										<span class="day">4</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">토</span>
										<span class="day">5</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">일</span>
										<span class="day">6</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">월</span>
										<span class="day">7</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">화</span>
										<span class="day">8</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">휴일</span>
										<span class="day">9</span>
									</a>
								</li>
							 	<li>
									<a href="#;" class="able">
										<span class="week">목</span>
										<span class="day">10</span>
									</a>
								</li>
							 	<li>
									<a href="#;" class="able">
										<span class="week">금</span>
										<span class="day">11</span>
									</a>
								</li>
							 	<li>
									<a href="#;" class="able">
										<span class="week">토</span>
										<span class="day">12</span>
									</a>
								</li>
							 	<li>
									<a href="#;" class="disable">
										<span class="week">일</span>
										<span class="day">13</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">월</span>
										<span class="day">14</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">화</span>
										<span class="day">15</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">수</span>
										<span class="day">16</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">목</span>
										<span class="day">17</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">금</span>
										<span class="day">18</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">토</span>
										<span class="day">19</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">일</span>
										<span class="day">20</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">월</span>
										<span class="day">21</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">화</span>
										<span class="day">22</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">수</span>
										<span class="day">23</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">목</span>
										<span class="day">24</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">금</span>
										<span class="day">25</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">토</span>
										<span class="day">26</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">일</span>
										<span class="day">27</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">월</span>
										<span class="day">28</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">화</span>
										<span class="day">29</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">수</span>
										<span class="day">30</span>
									</a>
								</li>
							 	<li>
									<a href="#;">
										<span class="week">목</span>
										<span class="day">31</span>
									</a>
								</li>
							 </ul>
						</div>
					</div>
				</div>
			</div>

			<!-- tabType1 -->
			<div class="tabType1">
				<ul>
                    <li class="<?=$_GET['boardid'] == 'all' ? 'active' : '' ?>"><a href="/center/schedule.php?boardid=all">전체</a></li>
                    <li class="<?=$_GET['boardid'] == 'edu' ? 'active' : '' ?>"><a href="/center/schedule.php?boardid=edu">교육</a></li>
                    <li class="<?=$_GET['boardid']== 'video' ? 'active' : '' ?>"><a href="/center/schedule.php?boardid=video">상영회</a></li>
				</ul>
			</div>
			<!-- //tabType1 -->

			<!-- subSec -->
			<div class="subSec last">
				<div class="inner">

					<!-- searchForm -->
					<div class="searchForm">
						<div class="count">
							전체 <span><?= number_format($totalCount) ?>건</span>
						</div>
						<div class="warnIco">
							매주 월요일, 일요일은 휴관일 입니다.
						</div>
					</div>
					<!-- //searchForm -->

                    <!-- Display combined list -->
                    <div id="list-all" style="<?= $_GET['boardid'] == 'all' ? 'display:block;' : 'display:none;' ?>">
                        <div class="listType1">
                            <ul>
                                <?php
                                if(!empty($combinedList)){
                                    foreach($combinedList as $item){
                                        $boardId = $item['type'];
                                        $imgsrc = "/uploaded/board/".$boardId."/".$item['re_name'];
                                        if(!$item['re_name']){
                                            $imgsrc = "/pub/images/img_gall_list_sample.png";
                                        }

                                        // Get current status and state class
                                        $currentStatus = $item['reception_status'];
                                        $stateClass = '';

                                        if ($currentStatus == '접수중' || $currentStatus == '상영중') {
                                            $stateClass = 'ing';
                                        } elseif ($currentStatus == '대기접수') {
                                            $stateClass = 'etc';
                                        } elseif ($currentStatus == '종료') {
                                            $stateClass = 'end';
                                        }

                                        // Different display for edu and video items
                                        if($item['type'] == 'edu') {
                                            ?>
                                            <li>
                                                <a href="<?= "/edu/list.php?boardid=edu&mode=view&idx=" . $item['idx']?>">
                                                    <div class="stateBox <?=$stateClass?>">
                                                        <span><?=$item['reception_status']?></span>
                                                    </div>
                                                    <div class="img">
                                                        <img src="<?=$imgsrc?>" alt="썸네일">
                                                    </div>
                                                    <div class="textWrap">
                                                        <div class="title"><?=$item['subject']?></div>
                                                        <div class="info">
                                                            <span class="left">교육기간</span>
                                                            <span class="right"><?=$item['e_start_date']?> ~ <?=$item['e_end_date']?></span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">교육시간</span>
                                                            <span class="right"><?=$item['start_hour']?>:<?=$item['start_minute']?> ~ <?=$item['end_hour']?>:<?=$item['end_minute']?></span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">회차</span>
                                                            <span class="right"><?=$item['etc_3']?>차시</span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">수강료</span>
                                                            <span class="right"><?=number_format($item['fee'])?>원</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li>
                                                <a href="<?= "/media/list.php?boardid=video&mode=view&idx=" . $item['idx']?>">
                                                    <div class="stateBox <?=$stateClass?>">
                                                        <span><?=$item['reception_status']?></span>
                                                    </div>
                                                    <div class="img">
                                                        <img src="<?=$imgsrc?>" alt="썸네일">
                                                    </div>
                                                    <div class="textWrap">
                                                        <div class="title"><div class="num"><?=$item['age_rating']?></div><?=$item['subject']?></div>
                                                        <div class="info">
                                                            <span class="left">상영일</span>
                                                            <span class="right"><?=$item['screening_date']?></span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">상영시간</span>
                                                            <span class="right"><?=$item['start_hour']?>:<?=$item['start_minute']?> ~ <?=$item['end_hour']?>:<?=$item['end_minute']?></span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">정보</span>
                                                            <span class="right"><?=$item['genre']?> / <?=$item['screening_time']?></span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">대상</span>
                                                            <span class="right"><?=$item['target']?></span>
                                                        </div>
                                                        <div class="info">
                                                            <span class="left">위치</span>
                                                            <span class="right"><?=$item['location']?></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- 전체 탭 페이징 -->
                        <div class="pagingWrap">
                            <?php
                            if($_GET['boardid'] == 'all') {
                                $queryString = explode("&", $_SERVER['QUERY_STRING']);
                                $reQueryString = "";
                                $comma = "";
                                for($i=0; $i<count($queryString); $i++){
                                    if(strpos($queryString[$i], "offset=") === false){
                                        $reQueryString .= $comma.$queryString[$i];
                                        $comma = "&";
                                    }
                                }
                                echo pageNavigationUser($totalItems, $scale, 10, $offset, $reQueryString);
                            }
                            ?>
                        </div>
                    </div>
					<!-- listType1 -->
                    <!--교육-->
                    <div id="list-edu" style="<?= $_GET['boardid'] == 'edu' ? 'display:block;' : 'display:none;' ?>">
                        <div class="listType1" >
                            <ul>
                                <?
                                if($arrBoardEduList["list"]["total"] > 0){
                                    for($i=0; $i < $arrBoardEduList["list"]["total"]; $i++){
                                        $eduBoardId = "edu";
                                        $imgsrc[$i] = "/uploaded/board/".$eduBoardId."/".$arrBoardEduList["list"][$i]['re_name'];
                                        if(!$arrBoardEduList["list"][$i]['re_name']){$imgsrc[$i] = "/pub/images/img_gall_list_sample.png";}
                                        ############################ 파일 확인 #############################
                                        $arrBoardArticle = getBoardArticleView($eduBoardId, "", $arrBoardEduList["list"][$i]['idx'],"list");
                                        for($j=0;$j<$arrBoardArticle["total_files"];$j++){
                                            if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
                                                $fileImg[$i] = '첨부파일';
                                            }

                                        }

                                        // Get the current state
                                        $currentStatus = $arrBoardEduList["list"][$i]['reception_status'];

                                        // Set the state class based on the current status using if statements
                                        if ($currentStatus == '접수중') {
                                            $stateClass = 'ing';
                                        } elseif ($currentStatus == '대기접수') {
                                            $stateClass = 'etc';
                                        } elseif ($currentStatus == '종료') {
                                            $stateClass = 'end';
                                        } elseif ($currentStatus == '교육중') {
                                            $stateClass = '';
                                        }

                                        ?>
                                        <li>
                                            <a href="<?= "/edu/list.php?boardid=" . $eduBoardId . "&mode=view&idx=" . $arrBoardEduList["list"][$i]['idx']?>">
                                                <div class="stateBox <?=$stateClass?>">
                                                    <span><?=$arrBoardEduList["list"][$i]['reception_status']?></span>
                                                </div>
                                                <div class="img">
                                                    <img src="<?=$imgsrc[$i]?>" alt="썸네일">
                                                </div>
                                                <div class="textWrap">
                                                    <div class="title"><?=$arrBoardEduList["list"][$i]['subject']?></div>
                                                    <div class="info">
                                                        <span class="left">교육기간</span>
                                                        <span class="right"><?=$arrBoardEduList["list"][$i]['e_start_date']?> ~ <?=$arrBoardEduList["list"][$i]['e_end_date']?></span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">교육시간</span>
                                                        <span class="right"><?=$dayType?> <?=$days?> <?=$arrBoardEduList["list"][$i]['start_hour']?>:<?=$arrBoardEduList["list"][$i]['start_minute']?> ~ <?=$arrBoardEduList["list"][$i]['end_hour']?>:<?=$arrBoardEduList["list"][$i]['end_minute']?></span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">회차</span>
                                                        <span class="right"><?=$arrBoardEduList["list"][$i]['etc_3']?>차시</span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">수강료</span>
                                                        <span class="right"><?=number_format($arrBoardEduList["list"][$i]['fee'])?>원</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- //listType1 -->

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
                            echo pageNavigationUser($arrBoardEduList["total"],10,10,$_GET['offset'],$reQueryString);
                            ############### paging ############### ED
                            ?>
                        </div>
                        <!-- //pagingWrap -->
                    </div>


                    <!--상영회-->
                    <!-- listType1 -->
                    <div id="list-video" style="<?= $_GET['boardid'] == 'video' ? 'display:block;' : 'display:none;' ?>">
                        <div class="listType1">
                            <ul>
                                <?
                                if($arrBoardVideoList["list"]["total"] > 0){
                                    for($i=0; $i < $arrBoardVideoList["list"]["total"]; $i++){
                                        $videoBoardId = "video";
                                        //파일
                                        $imgsrc[$i] = "/uploaded/board/".$videoBoardId."/".$arrBoardVideoList["list"][$i]['re_name'];
                                        if(!$arrBoardVideoList["list"][$i]['re_name']){$imgsrc[$i] = "/pub/images/img_gall_list_sample.png";}
                                        ############################ 파일 확인 #############################
                                        $arrBoardArticle = getBoardArticleView($videoBoardId, "", $arrBoardVideoList["list"][$i]['idx'],"list");
                                        for($j=0;$j<$arrBoardArticle["total_files"];$j++){
                                            if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
                                                $fileImg[$i] = '첨부파일';
                                            }

                                        }

                                        // Get the current state
                                        $currentStatus = $arrBoardVideoList["list"][$i]['reception_status'];

                                        // Set the state class based on the current status using if statements
                                        if ($currentStatus == '상영중') {
                                            $stateClass = 'ing';
                                        } elseif ($currentStatus == '대기접수') {
                                            $stateClass = 'etc';
                                        } elseif ($currentStatus == '종료') {
                                            $stateClass = 'end';
                                        } elseif ($currentStatus == '접수중') {
                                            $stateClass = 'ing';
                                        }

                                        ?>
                                        <li>
                                            <a href="<?= "/media/list.php?boardid=" . $videoBoardId . "&mode=view&idx=" . $arrBoardVideoList["list"][$i]['idx']?>">
                                                <div class="stateBox <?=$stateClass?>">
                                                    <span><?=$arrBoardVideoList["list"][$i]['reception_status']?></span>
                                                </div>
                                                <div class="img">
                                                    <img src="<?=$imgsrc[$i]?>" alt="썸네일">
                                                </div>
                                                <div class="textWrap">
                                                    <div class="title"><div class="num"><?=$arrBoardVideoList["list"][$i]['age_rating']?></div><?=$arrBoardVideoList["list"][$i]['subject']?></div>
                                                    <div class="info">
                                                        <span class="left">상영일</span>
                                                        <span class="right"><?=$arrBoardVideoList["list"][$i]['screening_date']?></span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">상영시간</span>
                                                        <span class="right"><?=$arrBoardVideoList["list"][$i]['start_hour']?>:<?=$arrBoardVideoList["list"][$i]['start_minute']?> ~ <?=$arrBoardVideoList["list"][$i]['end_hour']?>:<?=$arrBoardVideoList["list"][$i]['end_minute']?></span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">정보</span>
                                                        <span class="right"><?=$arrBoardVideoList["list"][$i]['genre']?> / <?=$arrBoardVideoList["list"][$i]['screening_time']?></span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">대상</span>
                                                        <span class="right"><?=$arrBoardVideoList["list"][$i]['target']?></span>
                                                    </div>
                                                    <div class="info">
                                                        <span class="left">위치</span>
                                                        <span class="right"><?=$arrBoardVideoList["list"][$i]['location']?></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- //listType1 -->

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
                            echo pageNavigationUser($arrBoardVideoList["total"],10,10,$_GET['offset'],$reQueryString);
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



