<?php
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include "./menu.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

// 연도 설정
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// 카테고리 목록 가져오기
$arrCategory = getCategoryList(62);
$categories = [];
foreach ($arrCategory["list"] as $item) {
	$categories[] = $item['cat_no'];
}

// 통계 데이터 가져오기 및 소계/합계 계산
$statistics = getEducationStatistics($year, $categories);
$statistics = calculateStatisticTotals($statistics);

// 소계 및 연간 총계 계산 함수
function calculateStatisticTotals($statistics) {
	$yearlyTotals = [
		'edu_count' => 0,
		'applicants_count' => 0,
		'certificates_count' => 0,
		'completion_rate' => 0,
		'total_amount' => 0
	];

	// 월별 소계 계산
	foreach ($statistics as $month => &$categories) {
		if (!is_numeric($month)) continue;

		$monthSubtotal = [
			'edu_count' => 0,
			'applicants_count' => 0,
			'certificates_count' => 0,
			'total_amount' => 0
		];

		foreach ($categories as $cat1 => $subcategories) {
			foreach ($subcategories as $cat2 => $data) {
				$monthSubtotal['edu_count'] += $data['edu_count'];
				$monthSubtotal['applicants_count'] += $data['applicants_count'];
				$monthSubtotal['certificates_count'] += $data['certificates_count'];
				$monthSubtotal['total_amount'] += $data['total_amount'];
			}
		}

		// 수강율 계산
		$monthSubtotal['completion_rate'] = ($monthSubtotal['applicants_count'] > 0) ?
			($monthSubtotal['certificates_count'] / $monthSubtotal['applicants_count']) * 100 : 0;

		// 월별 소계 저장
		$statistics[$month]['소계'] = $monthSubtotal;

		// 연간 총계에 더하기
		$yearlyTotals['edu_count'] += $monthSubtotal['edu_count'];
		$yearlyTotals['applicants_count'] += $monthSubtotal['applicants_count'];
		$yearlyTotals['certificates_count'] += $monthSubtotal['certificates_count'];
		$yearlyTotals['total_amount'] += $monthSubtotal['total_amount'];
	}

	// 연간 수강율 계산
	$yearlyTotals['completion_rate'] = ($yearlyTotals['applicants_count'] > 0) ?
		($yearlyTotals['certificates_count'] / $yearlyTotals['applicants_count']) * 100 : 0;

	// 연간 총계 저장
	$statistics['연간총계'] = $yearlyTotals;

	return $statistics;
}

// DB 해제
//SetDisConn($dblink);
?>

    <div class="container">
        <div class="title">연도별 교육 통계</div>
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
                        <a href="/backoffice/module/board/board_statistic_edu_xls.php?year=<?=$year?>" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
                    </div>
                </div>
            </div>

            <div class="over_tbl mo_break_tbl">
                <div class="bdr_list tac">
                    <table>
                        <colgroup class="pc_vw">
                            <col class="w10p">
                            <col class="w10p">
                            <col class="w10p">
                            <col class="w15p">
                            <col class="w15p">
                            <col class="w15p">
                            <col class="w15p">
                            <col class="w15p">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="pc_vw">월</th>
                            <th class="pc_vw" colspan="2">구분</th>
                            <th class="pc_vw">건수</th>
                            <th class="pc_vw">교육인원수</th>
                            <th class="pc_vw">수료인원</th>
                            <th class="pc_vw">수강율(%)</th>
                            <th class="pc_vw">금액</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						$currentMonth = null;
						$currentCategory1 = null;
						for ($month = 12; $month >= 1; $month--):
							if (isset($statistics[$month])):
								$rowCount = 0;
								foreach ($statistics[$month] as $cat1 => $subcategories) {
									if ($cat1 !== '소계') {
										$rowCount += count($subcategories);
									}
								}
								$firstRow = true;
								foreach ($statistics[$month] as $category1 => $subcategories):
									if ($category1 === '소계') continue; // 소계는 별도로 표시

									$firstCategoryRow = true;
									foreach ($subcategories as $category2 => $data): ?>
                                        <tr>
											<?php if ($firstRow): ?>
                                                <td rowspan="<?= $rowCount + 2 ?>"><!-- +2는 소계와 합계 행 --> <?= $month ?>월</td>
												<?php $firstRow = false; ?>
											<?php endif; ?>
											<?php if ($firstCategoryRow): ?>
                                                <td rowspan="<?= count($subcategories) ?>"><?= getCategoryName($category1); ?></td>
												<?php $firstCategoryRow = false; ?>
											<?php endif; ?>
                                            <td><?= getCategoryName($category2); ?></td>
                                            <td><?= $data['edu_count'] ?></td>
                                            <td><?= $data['applicants_count'] ?></td>
                                            <td><?= $data['certificates_count'] ?></td>
                                            <td><?= number_format($data['completion_rate'], 1) ?>%</td>
                                            <td><?= number_format($data['total_amount']) ?>원</td>
                                        </tr>
									<?php endforeach;
								endforeach;

								// 소계 표시
								if (isset($statistics[$month]['소계'])): ?>
                                    <tr class="total">
                                        <td colspan="2">소계</td>
                                        <td><?= $statistics[$month]['소계']['edu_count'] ?></td>
                                        <td><?= $statistics[$month]['소계']['applicants_count'] ?></td>
                                        <td><?= $statistics[$month]['소계']['certificates_count'] ?></td>
                                        <td><?= number_format($statistics[$month]['소계']['completion_rate'], 1) ?>%</td>
                                        <td><?= number_format($statistics[$month]['소계']['total_amount']) ?>원</td>
                                    </tr>

                                    <!-- 월별 합계 -->
                                    <tr class="month-total">
                                        <td colspan="2">합</td>
                                        <td><?= $statistics[$month]['소계']['edu_count'] ?>건</td>
                                        <td><?= $statistics[$month]['소계']['applicants_count'] ?>명</td>
                                        <td><?= $statistics[$month]['소계']['certificates_count'] ?>명</td>
                                        <td><?= number_format($statistics[$month]['소계']['completion_rate'], 1) ?>%</td>
                                        <td><?= number_format($statistics[$month]['소계']['total_amount']) ?>원</td>
                                    </tr>
								<?php endif;
							endif;
						endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
			<?php if (isset($statistics['연간총계'])): ?>
                <div class="bdr_list tac">
                    <table>
                        <tr class="year-total">
                            <td colspan="2"><?= $year ?>년 총합</td>
                            <td><?= $statistics['연간총계']['edu_count'] ?>건</td>
                            <td><?= $statistics['연간총계']['applicants_count'] ?>명</td>
                            <td><?= $statistics['연간총계']['certificates_count'] ?>명</td>
                            <td><?= number_format($statistics['연간총계']['completion_rate'], 1) ?>%</td>
                            <td><?= number_format($statistics['연간총계']['total_amount']) ?>원</td>
                        </tr>
                    </table>
                </div>
			<?php endif; ?>
        </div>
    </div>

    <style>
        .bdr_list tbody td, .bdr_list thead th {
            border-right: #ddd 1px solid;
            border-bottom: #ddd 1px solid;
        }
        .bdr_list thead th {
            border-top: #ddd 1px solid;
        }
        .total td {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .month-total td {
            background-color: #eee;
            font-weight: bold;
        }
        .year-total td {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .mt-20 {
            margin-top: 20px;
        }
    </style>
<?php include("pub/inc/footer.php") ?>