<?php
// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

// 연도와 월 설정
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// 해당 월의 마지막 날짜 구하기
$lastDay = date('t', strtotime($year.'-'.$month.'-01'));

$arrEquUser = getBoardArticleView("equ_applicants", "", "", "", "  equ_idx = " . $_GET['idx']);

// 대여 정보를 JSON으로 변환
$rentalData = [];
foreach ($arrEquUser["list"] as $rental) {
    $rentalData[] = [
        'start_date' => $rental['rental_start_date'],
        'end_date' => $rental['rental_end_date'],
        'start_time' => $rental['rental_start_time'],
        'end_time' => $rental['rental_end_time']
    ];
}

// 시간대 정의를 상단으로 이동
$timeSlots = [
    '10:00~11:00', '11:00~12:00', '12:00~13:00', '13:00~14:00',
    '14:00~15:00', '15:00~16:00', '16:00~17:00', '17:00~18:00',
    '18:00~19:00', '19:00~20:00', '20:00~21:00'
];

// 시간대별 예약 카운트를 저장할 배열 초기화
$totalQuantity = $arrBoardArticle["list"][0]["stock_quantity"];
$rentalCount = array();
for ($day = 1; $day <= $lastDay; $day++) {
    foreach ($timeSlots as $time) {
        $rentalCount[$day][$time] = $totalQuantity; // 초기값을 총 장비수로 설정
    }
}

// 휴관일 데이터
$arrBoardHolidayList = getBoardListBaseNFile("holiday", $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $_GET['reply']);

// 휴관일 데이터 처리 부분 수정
$currentYearMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
$holidayDates = [];

foreach ($arrBoardHolidayList['list'] as $holiday) {
    // 요일을 처리
    if (!empty($holiday['weekdays'])) {
        $weekdays = explode('|', $holiday['weekdays']);
        $firstDayOfMonth = $currentYearMonth . '-01';
        $lastDayOfMonth = $currentYearMonth . '-' . $lastDay;
        $currentDate = new DateTime($firstDayOfMonth);
        $lastDate = new DateTime($lastDayOfMonth);

        while ($currentDate <= $lastDate) {
            $weekday = $currentDate->format('w'); // 0 (일요일) ~ 6 (토요일)
            if (in_array(["일", "월", "화", "수", "목", "금", "토"][$weekday], $weekdays)) {
                $holidayDates[] = $currentDate->format('Y-m-d');
            }
            $currentDate->modify('+1 day');
        }
    }

    // 특정 날짜 범위를 처리
    if (!empty($holiday['holly_start_date']) && !empty($holiday['holly_end_date'])) {
        $startDate = new DateTime($holiday['holly_start_date']);
        $endDate = new DateTime($holiday['holly_end_date']);

        // 이번 달의 시작일과 종료일
        $monthStart = new DateTime($currentYearMonth . '-01');
        $monthEnd = new DateTime($currentYearMonth . '-' . $lastDay);

        // 휴관일 기간이 현재 월과 겹치는 경우만 처리
        if ($startDate <= $monthEnd && $endDate >= $monthStart) {
            $periodStart = max($startDate, $monthStart);
            $periodEnd = min($endDate, $monthEnd);

            $currentDate = clone $periodStart;
            while ($currentDate <= $periodEnd) {
                $holidayDates[] = $currentDate->format('Y-m-d');
                $currentDate->modify('+1 day');
            }
        }
    }
}

// 중복날짜 제거
$holidayDates = array_unique($holidayDates);

// 예약 데이터를 순회하면서 카운트 계산
foreach ($rentalData as $rental) {
    $start_date = new DateTime($rental['start_date']);
    $end_date = new DateTime($rental['end_date']);
    $start_time = substr($rental['start_time'], 0, 5);
    $end_time = substr($rental['end_time'], 0, 5);

    for ($current_date = clone $start_date; $current_date <= $end_date; $current_date->modify('+1 day')) {
        if ($current_date->format('Y-m') == "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT)) {
            $day = intval($current_date->format('d'));

            foreach ($timeSlots as $time) {
                list($slot_start, $slot_end) = explode('~', $time);
                $slot_start = trim($slot_start);
                $slot_end = trim($slot_end);

                // 시작일과 종료일이 같은 경우 (당일 대여)
                if ($start_date->format('Y-m-d') === $end_date->format('Y-m-d')) {
                    if ($slot_start >= $start_time && $slot_start < $end_time) {
                        $rentalCount[$day][$time]--;
                    }
                }
                // 첫째 날인 경우
                else if ($current_date->format('Y-m-d') === $start_date->format('Y-m-d')) {
                    if ($slot_start >= $start_time) {
                        $rentalCount[$day][$time]--;
                    }
                }
                // 마지막 날인 경우
                else if ($current_date->format('Y-m-d') === $end_date->format('Y-m-d')) {
                    if ($slot_start < $end_time) {
                        $rentalCount[$day][$time]--;
                    }
                }
                // 중간 날짜들인 경우
                else {
                    $rentalCount[$day][$time]--;
                }
            }
        }
    }
}
?>

<div class="container">
    <div class="title">장비대여 시간대별 통계</div>
    <div class="inbox">
        <div class="bdr_top">
            <div class="left"></div>
            <div class="bdr_right">
                <div class="btns">
                    <a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&category=<?=$_GET['category']?>" class="btn btn_list">목록보기</a>
                </div>
            </div>
        </div>
        <!-- 장비 상세 정보 테이블 -->
        <div class="over_tbl mo_break_tbl">
            <div class="bdr_list tac">
                <table>
                    <thead>
                    <tr>
                        <th style="width:25%">구분</th>
                        <th style="width:75%">내용</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>카테고리</td>
                        <td class="tal"><?=getCategoryName($arrBoardArticle["list"][0]["category1"])?></td>
                    </tr>
                    <tr>
                        <td>장비번호</td>
                        <td class="tal"><?=$arrBoardArticle["list"][0]["equ_number"]?></td>
                    </tr>
                    <tr>
                        <td>장비명</td>
                        <td class="tal"><?=$arrBoardArticle["list"][0]["subject"]?></td>
                    </tr>
                    <tr>
                        <td>총 장비수</td>
                        <td class="tal"><?=$arrBoardArticle["list"][0]["stock_quantity"]?></td>
                    </tr>
                    <tr>
                        <td>대여료</td>
                        <td class="tal"><?=number_format($arrBoardArticle["list"][0]["fee"])?>원/ 1일기준</td>
                    </tr>
                    <tr>
                        <td>이용등급</td>
                        <td class="tal"><?=$arrBoardArticle["list"][0]["usage_level"]?></td>
                    </tr>
                    <tr>
                        <td>순번</td>
                        <td class="tal"><?=$arrBoardArticle["list"][0]["b_sort"]?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 월 선택 영역 -->
        <div class="block-area" style="margin:30px 0;">
            <dl class="w2">
                <dd>
                    <button type="button" onclick="location.href='?boardid=equ_statistic&mode=view&idx=<?=$_GET['idx']?>&year=<?=$year?>&month=<?=$month-1 <= 0 ? 12 : $month-1?><?=$month-1 <= 0 ? '&year='.($year-1) : ''?>'" class="btn">◀</button>
                    <span style="margin: 0 20px; font-size: 20px;"><strong><?=$year?>년 <?=$month?>월</strong></span>
                    <button type="button" onclick="location.href='?boardid=equ_statistic&mode=view&idx=<?=$_GET['idx']?>&year=<?=$year?>&month=<?=$month+1 > 12 ? 1 : $month+1?><?=$month+1 > 12 ? '&year='.($year+1) : ''?>'" class="btn">▶</button>
                    &nbsp;&nbsp;(총 장비수 : <?=$arrBoardArticle["list"][0]["stock_quantity"]?>개)
                </dd>
            </dl>
        </div>
        <!-- 시간대별 통계 테이블 -->
        <div class="over_tbl mo_break_tbl">
            <div class="bdr_list tac">
                <table>
                    <thead>
                    <tr>
                        <th>시간/일</th>
                        <?php for($day = 1; $day <= $lastDay; $day++): ?>
                            <th><?=$day?></th>
                        <?php endfor; ?>
                    </tr>
                    </thead>
                    <!-- 시간대별 통계 테이블 수정 -->
                    <tbody>
                    <?php foreach($timeSlots as $time): ?>
                        <tr>
                            <?php
                            $times = explode('~', $time);
                            ?>
                            <td>
                                <?=$times[0]?><br>
                                ~<br>
                                <?=$times[1]?>
                            </td>
                            <?php for($day = 1; $day <= $lastDay; $day++):
                                $currentDate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                $isHoliday = in_array($currentDate, $holidayDates);
                                ?>
                                <td style="background-color: <?php
                                if ($isHoliday) {
                                    echo '#f0f0f0';
                                } elseif ($rentalCount[$day][$time] == 0) {
                                    echo '#f0f0f0';
                                } else {
                                    echo '#fff';
                                }
                                ?>">
                                    <?php if (!$isHoliday): ?>
                                        <?=$rentalCount[$day][$time]?>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bdr_list {
        overflow-x: auto;
    }
    .bdr_list table {
        min-width: 100%;
        border-collapse: collapse;
    }
    .bdr_list tbody td,
    .bdr_list thead th {
        border: 1px solid #ddd;
        padding: 8px;
        min-width: 60px;
        text-align: center;
    }
    .bdr_list thead th {
        font-weight: bold;
    }
    .bdr_list tbody td:first-child {
        background-color: rgb(227, 236, 249) !important;
        font-weight: bold;
    }
</style>

<?php include("pub/inc/footer.php") ?>
