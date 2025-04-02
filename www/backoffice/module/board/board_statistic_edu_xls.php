<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("login");
	jsHistory("-1");
endif;

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

// 카테고리 목록 가져오기
$arrCategory = getCategoryList(62);
$categories = [];
foreach ($arrCategory["list"] as $item) {
	$categories[] = $item['cat_no'];
}

// 통계 데이터 가져오기
$statistics = getEducationStatistics($year, $categories);

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

// 소계와 총계 계산
$statistics = calculateStatisticTotals($statistics);

$filename = $_SITE['NAME'] . "_연도별교육통계_" . date('YmdHi') . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Description: PHP4 Generated Data");
header("Pragma: no-cache");
header("Expires: 0");

// Add BOM to fix UTF-8 in Excel
echo "\xEF\xBB\xBF";

$EXCEL_TXT = "
<table border='1'>
    <tr>
        <td colspan='8' style='text-align:center; font-weight:bold;'>{$year}년 교육 통계</td>
    </tr>
    <tr>
        <td>월</td>
        <td colspan='2'>구분</td>
        <td>건수</td>
        <td>교육인원수</td>
        <td>수료인원</td>
        <td>수강율(%)</td>
        <td>금액</td>
    </tr>";

for ($month = 12; $month >= 1; $month--) {
	if (isset($statistics[$month])) {
		// 첫 번째 행인지 확인용 변수
		$firstRow = true;

		foreach ($statistics[$month] as $category1 => $subcategories) {
			// '소계'는 별도로 처리하므로 건너뛰기
			if ($category1 === '소계') continue;

			foreach ($subcategories as $category2 => $data) {
				$EXCEL_TXT .= "
                <tr>
                    <td>{$month}월</td>
                    <td>" . getCategoryName($category1) . "</td>
                    <td>" . getCategoryName($category2) . "</td>
                    <td>{$data['edu_count']}</td>
                    <td>{$data['applicants_count']}</td>
                    <td>{$data['certificates_count']}</td>
                    <td>" . number_format($data['completion_rate'], 1) . "%</td>
                    <td>" . number_format($data['total_amount']) . "</td>
                </tr>";
			}
		}

		// 소계 및 합계 추가
		if (isset($statistics[$month]['소계'])) {
			// 소계 행
			$EXCEL_TXT .= "
            <tr>
                <td>{$month}월</td>
                <td colspan='2' style='background-color:#f5f5f5; font-weight:bold;'>소계</td>
                <td style='background-color:#f5f5f5; font-weight:bold;'>{$statistics[$month]['소계']['edu_count']}</td>
                <td style='background-color:#f5f5f5; font-weight:bold;'>{$statistics[$month]['소계']['applicants_count']}</td>
                <td style='background-color:#f5f5f5; font-weight:bold;'>{$statistics[$month]['소계']['certificates_count']}</td>
                <td style='background-color:#f5f5f5; font-weight:bold;'>" . number_format($statistics[$month]['소계']['completion_rate'], 1) . "%</td>
                <td style='background-color:#f5f5f5; font-weight:bold;'>" . number_format($statistics[$month]['소계']['total_amount']) . "</td>
            </tr>";

			// 합계 행
			$EXCEL_TXT .= "
            <tr>
                <td>{$month}월</td>
                <td colspan='2' style='background-color:#eee; font-weight:bold;'>합</td>
                <td style='background-color:#eee; font-weight:bold;'>{$statistics[$month]['소계']['edu_count']}건</td>
                <td style='background-color:#eee; font-weight:bold;'>{$statistics[$month]['소계']['applicants_count']}명</td>
                <td style='background-color:#eee; font-weight:bold;'>{$statistics[$month]['소계']['certificates_count']}명</td>
                <td style='background-color:#eee; font-weight:bold;'>" . number_format($statistics[$month]['소계']['completion_rate'], 1) . "%</td>
                <td style='background-color:#eee; font-weight:bold;'>" . number_format($statistics[$month]['소계']['total_amount']) . "원</td>
            </tr>";
		}
	}
}

// 연간 총계 추가
if (isset($statistics['연간총계'])) {
	$EXCEL_TXT .= "
    <tr>
        <td colspan='3' style='background-color:#e0e0e0; font-weight:bold;'>{$year}년 총합</td>
        <td style='background-color:#e0e0e0; font-weight:bold;'>{$statistics['연간총계']['edu_count']}건</td>
        <td style='background-color:#e0e0e0; font-weight:bold;'>{$statistics['연간총계']['applicants_count']}명</td>
        <td style='background-color:#e0e0e0; font-weight:bold;'>{$statistics['연간총계']['certificates_count']}명</td>
        <td style='background-color:#e0e0e0; font-weight:bold;'>" . number_format($statistics['연간총계']['completion_rate'], 1) . "%</td>
        <td style='background-color:#e0e0e0; font-weight:bold;'>" . number_format($statistics['연간총계']['total_amount']) . "원</td>
    </tr>";
}

$EXCEL_TXT .= "</table>";

echo $EXCEL_TXT;

SetDisConn($dblink);
?>