<?php
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/pop_top.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/calendar/calendar.lib.php";

$dblink = SetConn($_conf_db["main_db"]);

if(!$_GET['sYear']){
    $_GET['sYear'] = date("Y");
}
if(!$_GET['sMonth']){
    $_GET['sMonth'] = date("m");
}
$cal_date = $_GET['sYear'].'-'.$_GET['sMonth'].'-01';

$arrDate = explode("-",$cal_date);
$arrSolarCalendar = getDiarySet(intval($arrDate[0]), intval($arrDate[1]), intval($arrDate[2]));

// boardid 매핑 배열 추가
$boardidMap = array(
    "A" => "edu",
    "B" => "equ_applicants",
    "C" => "place_applicants",
    "D" => "media_applicants",
    "E" => "video"
);

if ($_GET['tab']) {
    $boardid = $boardidMap[$_GET['tab']];
    $arrBoardList = getBoardListScheduleList($boardid, $arrSolarCalendar['first_before'], $arrSolarCalendar['last_after']);
} else {
    // 전체탭일 경우 모든 게시판의 일정을 가져옴
    $arrBoardList = array('list' => array());
    foreach ($boardidMap as $boardid) {
        $tempList = getBoardListScheduleList($boardid, $arrSolarCalendar['first_before'], $arrSolarCalendar['last_after']);
        if (isset($tempList['list']) && is_array($tempList['list'])) {
            foreach ($tempList['list'] as $date => $items) {
                if (!isset($arrBoardList['list'][$date])) {
                    $arrBoardList['list'][$date] = array();
                }
                foreach ($items as $item) {
                    // 게시판 타입 정보 추가
                    $item['board_type'] = array_search($boardid, $boardidMap);
                    $arrBoardList['list'][$date][] = $item;
                }
            }
        }
    }
}

// 휴관일 데이터
$arrBoardHolidayList = getBoardListBaseNFile("holiday", $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $_GET['reply']);

$holidayDates = [];
foreach ($arrBoardHolidayList['list'] as $holiday) {
    // 요일을 처리
    if (!empty($holiday['weekdays'])) {
        $weekdays = explode('|', $holiday['weekdays']);
        foreach ($weekdays as $weekday) {
            for ($i = 0; $i < count($arrSolarCalendar['box']); $i++) {
                for ($j = 0; $j < 7; $j++) {
                    $currentDate = $arrSolarCalendar['box'][$i][$j];
                    if (date('w', strtotime($currentDate)) == array_search($weekday, ["일", "월", "화", "수", "목", "금", "토"])) {
                        $holidayDates[] = $currentDate;
                    }
                }
            }
        }
    }

    // 특정 날짜 범위를 처리
    if (!empty($holiday['holly_start_date']) && !empty($holiday['holly_end_date'])) {
        $startDate = strtotime($holiday['holly_start_date']);
        $endDate = strtotime($holiday['holly_end_date']);
        for ($date = $startDate; $date <= $endDate; $date = strtotime('+1 day', $date)) {
            $holidayDates[] = date('Y-m-d', $date);
        }
    }
}

// 중복날짜 제거
$holidayDates = array_unique($holidayDates);

$prev_date = date("Y-m-d",strtotime($cal_date.'-1 month'));
$next_date = date("Y-m-d",strtotime($cal_date.'+1 month'));
?>
    <style type="text/css">
        .other { background:#e6e6e6; }
        .date-cell { cursor: pointer; }
        .date-cell:hover { background-color: #f0f0f0; }
        .schedule-count { color: #666; font-size: 0.9em; }
        .holiday-cell {
            background-color: #ffebeb;
            cursor: default;
        }
        .holiday-text {
            color: #ff0000;
        }
        .schedule_area td.today .day {
            background: #0348a5;
        }
    </style>
    <style>
        .tab_div {
            display:flex;flex-direction: row;align-items: center;justify-content: flex-start; gap:8px; margin-bottom:15px;
        }
        .tab_div .tab_menu {
            cursor:pointer;display:flex;align-items: center;justify-content: center;width: 150px;border: 1px solid #628dc7;border-radius: 5px;text-align: center;height: 30px;
        }
        .tab_div .tab_menu:hover,
        .tab_div .tab_menu.on {
            background-color:#628dc7;
            color:#ffffff;
        }
        .tab_div .tab_menu.cal {
            background-color: #305587;
            color:#ffffff;
        }

        .schedule_type {
            margin-top: 30px;
        }

        .schedule_name {
            font-weight: bold;
            margin-bottom: 5px;
            padding: 5px 10px;
            border: 1px solid #628dc7;
            border-radius: 5px;
            background-color: #628dc7;
            color: #ffffff;
            display: inline-block;
        }

        .holiday_buttons {
            display: flex;
            margin-top: 20px;
        }

        .holiday_buttons .btn {
            margin-left: 0 !important;
            margin-right: 20px !important;
        }
    </style>
    <script type="text/javascript">
        function showDateDetails(date) {
            // Hide all schedule rows first
            const allRows = document.querySelectorAll('.schedule-row');
            allRows.forEach(row => {
                row.style.display = 'none';
            });

            // Show only rows matching the selected date
            const dateRows = document.querySelectorAll('.schedule-' + date);
            dateRows.forEach(row => {
                row.style.display = 'table-row';
            });

            // Highlight selected date
            const allCells = document.querySelectorAll('.date-cell');
            allCells.forEach(cell => {
                cell.classList.remove('selected');
            });
            document.querySelector(`[data-date="${date}"]`).classList.add('selected');
        }
    </script>
<?php
$arrCategory = array(
    "A" => "교육",
    "B" => "장비",
    "C" => "공간",
    "D" => "체험",
    "E" => "상영회"
);
?>
    <div class="container">
        <div class="title">전체일정</div>
        <!--<div class="tab_div">
            <div class='tab_menu <?php /*=$_GET["tab"] == ""?"on":""*/?>' onclick="location.href='<?php /*=$_SERVER["PHP_SELF"]*/?>'">전체</div>
            <?php /*foreach($arrCategory as $key => $val){*/?>
                <div class='tab_menu <?php /*=$_GET["tab"] == $key?"on":""*/?>' onclick="location.href='<?php /*=$_SERVER["PHP_SELF"]*/?>?tab=<?php /*=$key*/?>'"><?php /*=$val*/?></div>
            <?php /*} */?>
        </div>-->

        <div class="inbox write_tbl schedule_wrap">
            <div class="schedule_area">
                <div class="years">
                    <button type="button" onclick="location.href='<?=$_SERVER["PHP_SELF"]?>?sYear=<?=substr($prev_date,0,4)?>&sMonth=<?=substr($prev_date,5,2)?><?=$_GET["tab"]?"&tab=".$_GET["tab"]:""?>'" class="btn prev">이전</button>
                    <span><?=str_replace("-",". ",substr($cal_date,0,7))?></span>
                    <button type="button" onclick="location.href='<?=$_SERVER["PHP_SELF"]?>?sYear=<?=substr($next_date,0,4)?>&sMonth=<?=substr($next_date,5,2)?><?=$_GET["tab"]?"&tab=".$_GET["tab"]:""?>'" class="btn next">다음</button>
                    <button type="button" onclick="location.href='<?=$_SERVER["PHP_SELF"]?><?=$_GET["tab"]?"?tab=".$_GET["tab"]:""?>'" class="btn today">오늘</button>
                </div>
                <table>
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
                    <?php
                    for($i=0; $i<count($arrSolarCalendar["box"]); $i++) {
                        echo "<tr>";
                        for($j=0; $j<7; $j++) {
                            $currentDate = $arrSolarCalendar["box"][$i][$j];

                            // Determine cell classes
                            $cal_class = ($j == 0) ? "cal_sun" : ($j == 6 ? "cal_sat" : "cal_day");
                            $cal_td = (substr($cal_date,0,7) == substr($currentDate,0,7)) ? "" : "other";
                            if($currentDate == date("Y-m-d")) {
                                $cal_td .= " today";
                            }

                            // Count schedules
                            $eduCount = 0;
                            $calculateCount = 0;

                            if(is_array($arrBoardList["list"][$currentDate])) {
                                $eduCount = count($arrBoardList["list"][$currentDate]);
                            }

                            // Generate cell content
                            $countDisplay = '';
                            if (in_array($currentDate, $holidayDates)) {
                                $countDisplay .= "<div class='schedule-count holiday-text'>휴관일</div>";
                                ?>
                                <td class="date-cell <?=$cal_td?> <?=$cal_class?> holiday-cell"
                                    data-date="<?=$currentDate?>">
                                    <span class="day"><?=substr($currentDate,-2)?></span>
                                    <?=$countDisplay?>
                                </td>
                                <?php
                            } else {
                                if($eduCount > 0) {
                                    $countDisplay .= "<div class='schedule-count'>+{$eduCount}</div>";
                                }
                                ?>
                                <td class="date-cell <?=$cal_td?> <?=$cal_class?>"
                                    onclick="showDateDetails('<?=$currentDate?>')"
                                    data-date="<?=$currentDate?>">
                                    <span class="day"><?=substr($currentDate,-2)?></span>
                                    <?=$countDisplay?>
                                </td>
                                <?php
                            }
                        }
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="holiday_buttons">
                    <button type="button" onclick="OpenPersonView('holiday')" class="btn">휴관일 신규등록</button>
                    <button type="button" onclick="OpenPersonList('holiday')" class="btn">휴관일 삭제</button>
                </div>
            </div>

            <div class="week_area">
                <table>
                    <?php
                    for($i=0; $i<count($arrSolarCalendar["box"]); $i++) {
                        for($j=0; $j<7; $j++) {
                            $currentDate = $arrSolarCalendar["box"][$i][$j];
                            if (!empty($arrBoardList["list"][$currentDate])) {
                                ?>
                                <tr class="schedule-row schedule-<?=$currentDate?>" style="display: none;">
                                    <th>
                                        <?=str_replace("-","/",substr($currentDate,-5))?> (<?=weekday($currentDate)?>)
                                    </th>
                                    <td>
                                        <?php
                                        // 같은 날짜의 일정들을 타입별로 그룹화
                                        $groupedSchedules = [];
                                        foreach($arrBoardList["list"][$currentDate] AS $val) {
                                            $scheduleType = isset($val['board_type']) ? $val['board_type'] : $_GET['tab'];
                                            $groupedSchedules[$scheduleType][] = $val;
                                        }

                                        // 일정 출력 부분 수정
                                        foreach($groupedSchedules as $scheduleType => $schedules): ?>
                                            <div class="schedule_type">
                                                <div class="schedule_name"><?=$arrCategory[$scheduleType]?></div>
                                                <?php foreach($schedules as $val): ?>
                                                    <div>
                                                        <a href="/backoffice/module/board/board_view.php?boardid=<?=$val['schedule_source']?>&mode=modify&idx=<?=$val['idx']?>" target="_parent">
                                                            <?php if ($boardidMap[$scheduleType] == 'equ_applicants' ||
                                                                $boardidMap[$scheduleType] == 'place_applicants' ||
                                                                $boardidMap[$scheduleType] == 'media_applicants'): ?>
                                                                <?=$val['name']?> /
                                                            <?php endif; ?>
                                                            <?=$val['subject']?>
	                                                        <?php if ($boardidMap[$scheduleType] == 'edu'): ?>
                                                                / <?=$val['e_start_date']?> ~ <?=$val['e_end_date']?> / <?=$val['start_hour']?>:<?=$val['start_minute']?> ~ <?=$val['end_hour']?>:<?=$val['end_minute']?>
	                                                        <?php endif; ?>
	                                                        <?php if ($boardidMap[$scheduleType] == 'equ_applicants'): ?>
                                                                / <?=$val['rental_start_date']?> ~ <?=$val['rental_end_date']?> / <?=$val['rental_start_time']?> ~ <?=$val['rental_end_time']?>
	                                                        <?php endif; ?>
	                                                        <?php if ($boardidMap[$scheduleType] == 'place_applicants'): ?>
                                                                / <?=$val['rental_start_time']?> ~ <?=$val['rental_end_time']?>
	                                                        <?php endif; ?>
                                                            <?php if ($boardidMap[$scheduleType] == 'media_applicants'): ?>
                                                                <?= str_replace('|', ',', $val['experience']) ?> / <?=$val['start_hour']?>:<?=$val['start_minute']?> ~ <?=$val['end_hour']?>:<?=$val['end_minute']?>
                                                            <?php endif; ?>
	                                                        <?php if ($boardidMap[$scheduleType] == 'video'): ?>
		                                                        <?=$val['start_hour']?>:<?=$val['start_minute']?> ~ <?=$val['end_hour']?>:<?=$val['end_minute']?>
	                                                        <?php endif; ?>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const today = '<?= date("Y-m-d") ?>';
            showDateDetails(today);
        });
    </script>
<?######################################### iframe fancybox ######################################### ST?>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <style type="text/css">
        .fancybox__content { padding: 5px 0;border-radius: 4px; }
        .fancybox__slide {padding-bottom:20px;}

        /* Ensure the select box is above the file input */
        select[name="waitlist"] {
            position: relative;
            z-index: 10;
        }

        /* Adjust the file input container */
        .filebutton {
            position: relative;
            z-index: 1;
        }
    </style>
    <script type="text/javascript">
        <!--
        function OpenPersonView(fname)
        {
            var requestUrl = "/backoffice/module/board/pop_board_view.php?boardid=holiday&mode=write&fname="+fname;	// 일반게시판

            Fancybox.show([
                {
                    src: requestUrl,
                    type: "iframe",
                    preload: false,
                    width: 1100,
                    height: 700
                },
            ]);
        }

        function OpenPersonList(fname)
        {
            var requestUrl = "/backoffice/module/board/pop_board_view.php?boardid=holiday&fname="+fname;

            Fancybox.show([
                {
                    src: requestUrl,
                    type: "iframe",
                    preload: false,
                    width: 1100,
                    height: 700
                },
            ]);
        }
        //-->

    </script>
<?######################################### iframe fancybox ######################################### ED?>