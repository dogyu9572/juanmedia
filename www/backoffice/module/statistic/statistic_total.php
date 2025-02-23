<?php
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$monthlyCounts = getMonthlyCountsByYear($year);

function getCount($monthlyCounts, $table, $month) {
	foreach ($monthlyCounts[$table] as $data) {
		if ($data['month'] == $month) {
			return $data['total'];
		}
	}
	return 0;
}

// 데이터가 있는 월 찾기
$activeMonths = [];
for ($i = 12; $i >= 1; $i--) {
	// 각 월별로 모든 데이터 항목을 검사
	$hasData = false;
	$tables = ['edu', 'edu_applicants', 'equ', 'equ_applicants', 'place', 'place_applicants', 'video', 'video_applicants', 'media', 'media_applicants'];

	foreach ($tables as $table) {
		if (getCount($monthlyCounts, $table, $i) > 0) {
			$hasData = true;
			break;
		}
	}

	if ($hasData) {
		$activeMonths[] = $i;
	}
}

// DB 해제
SetDisConn($dblink);
?>

    <div class="container">
        <div class="title">전체통계</div>

        <div class="inbox">
            <div class="block-area">
                <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>" name="logViewFrm">
                    <dl class="w2">
                        <dd>
                            <button type="button" onclick="location.href='?year=<?= $year - 1 ?>'" class="btn">◀</button>
                            <span style="margin: 0 20px; font-size: 20px;"><strong><?= $year ?>년</strong></span>
                            <button type="button" onclick="location.href='?year=<?= $year + 1 ?>'" class="btn">▶</button>
                        </dd>
                    </dl>
                </form>
            </div>

            <div class="bdr_top">
                <div class="left"></div>
                <div class="bdr_right">
                    <div class="btns">
                        <a href="/backoffice/module//board/board_statistic_total_xls.php?year=<?=$year?>" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
                    </div>
                </div>
            </div>
            <div class="over_tbl mo_break_tbl">
                <div class="bdr_list tac">
                    <table>
                        <colgroup class="pc_vw">
                            <col class="w10p">
                            <col class="w20p">
                            <col class="w20p">
                            <col class="w30p">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="pc_vw">월</th>
                            <th class="pc_vw" colspan="2">구분</th>
                            <th class="pc_vw">합계</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ($activeMonths as $i): ?>
                            <tr>
                                <td rowspan="10"><?= $i ?>월</td>
                                <td rowspan="2">교육</td>
                                <td>프로그램 수(개)</td>
                                <td><?= getCount($monthlyCounts, 'edu', $i) ?></td>
                            </tr>
                            <tr>
                                <td>참여인원수(명)</td>
                                <td><?= getCount($monthlyCounts, 'edu_applicants', $i) ?></td>
                            </tr>
                            <tr>
                                <td rowspan="2">장비대여</td>
                                <td>대여건수(일)</td>
                                <td><?= getCount($monthlyCounts, 'equ', $i) ?></td>
                            </tr>
                            <tr>
                                <td>장비이용자수(명)</td>
                                <td><?= getCount($monthlyCounts, 'equ_applicants', $i) ?></td>
                            </tr>
                            <tr>
                                <td rowspan="2">공간대여</td>
                                <td>대여건수(일)</td>
                                <td><?= getCount($monthlyCounts, 'place', $i) ?></td>
                            </tr>
                            <tr>
                                <td>공간이용자수(명)</td>
                                <td><?= getCount($monthlyCounts, 'place_applicants', $i) ?></td>
                            </tr>
                            <tr>
                                <td rowspan="2">상영회</td>
                                <td>건수</td>
                                <td><?= getCount($monthlyCounts, 'video', $i) ?></td>
                            </tr>
                            <tr>
                                <td>상영회인원수(명)</td>
                                <td><?= getCount($monthlyCounts, 'video_applicants', $i) ?></td>
                            </tr>
                            <tr>
                                <td rowspan="2">미디어체험</td>
                                <td>건수</td>
                                <td><?= getCount($monthlyCounts, 'media', $i) ?></td>
                            </tr>
                            <tr>
                                <td>체험인원수(명)</td>
                                <td><?= getCount($monthlyCounts, 'media_applicants', $i) ?></td>
                            </tr>
						<?php endforeach; ?>
                        <tr>
                            <td rowspan="2">총합</td>
                            <td>교육연인원+장비이용자수+공간이용자수+기타이용자</td>
                            <td>이용자(명)</td>
                            <td><?=$monthlyCounts["total_users"]?></td>
                        </tr>
                        <tr>
                            <td>교육프로그램수+기타프로그램</td>
                            <td>프로그램 수(개)</td>
                            <td><?=$monthlyCounts["total_programs"]?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        .bdr_list tbody td {
            border-right: #ddd 1px solid;
        }
        .block-area {
            padding-bottom: 20px;
        }
    </style>

<?php include("pub/inc/footer.php") ?>