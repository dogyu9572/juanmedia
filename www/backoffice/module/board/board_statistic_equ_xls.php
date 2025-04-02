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
$arrCategory = getCategoryList(35);
$categories = [];
foreach ($arrCategory["list"] as $item) {
	$categories[intval($item['cat_no'])] = $item['cat_name'];
}

// 통계 데이터 가져오기
$statistics = getEquipmentStatistics($year);

$filename = $_SITE['NAME'] . "_장비대여통계_" . date('YmdHi') . ".xls";
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
        <td colspan='18' style='text-align:center; font-weight:bold;'>{$year}년 장비대여 통계</td>
    </tr>
    <tr>
        <td rowspan='2'>월</td>
        <td rowspan='2'>구분</td>
        <td rowspan='2'>장비명</td>
        <td colspan='5'>건수</td>
        <td colspan='5'>대여일</td>
        <td colspan='5'>이용 인원(명)</td>
    </tr>
    <tr>
        <td>유료</td>
        <td>교육</td>
        <td>지원</td>
        <td>할인</td>
        <td>유료+지원</td>
        <td>유료</td>
        <td>교육</td>
        <td>지원</td>
        <td>할인</td>
        <td>유료+지원</td>
        <td>유료</td>
        <td>교육</td>
        <td>지원</td>
        <td>할인</td>
        <td>유료+지원</td>
    </tr>";

for ($month = 12; $month >= 1; $month--) {
	if (isset($statistics[$month])) {
		foreach ($statistics[$month] as $cat_no => $categoryData) {
			if ($cat_no !== '소계') {
				foreach ($categoryData as $subject => $data) {
					$EXCEL_TXT .= "
                    <tr>
                        <td>{$month}월</td>
                        <td>" . (isset($categories[$cat_no]) ? $categories[$cat_no] : $cat_no) . "</td>
                        <td>{$subject}</td>";

					// 건수
					foreach (['유료', '교육', '지원', '할인', '유료+지원'] as $type) {
						$EXCEL_TXT .= "<td>" . (isset($data['counts'][$type]) ? $data['counts'][$type] : 0) . "</td>";
					}

					// 대여일
					foreach (['유료', '교육', '지원', '할인', '유료+지원'] as $type) {
						$EXCEL_TXT .= "<td>" . (isset($data['days'][$type]) ? $data['days'][$type] : 0) . "</td>";
					}

					// 이용인원
					foreach (['유료', '교육', '지원', '할인', '유료+지원'] as $type) {
						$EXCEL_TXT .= "<td>" . (isset($data['people'][$type]) ? $data['people'][$type] : 0) . "</td>";
					}

					$EXCEL_TXT .= "</tr>";
				}
			}
		}

		// 월별 소계
		if (isset($statistics[$month]['소계'])) {
			$EXCEL_TXT .= "
            <tr>
                <td colspan='3' style='background-color:#f5f5f5; font-weight:bold;'>소계</td>";

			// 건수 소계
			foreach (['유료', '교육', '지원', '할인', '유료+지원'] as $type) {
				$EXCEL_TXT .= "<td style='background-color:#f5f5f5; font-weight:bold;'>" . $statistics[$month]['소계']['counts'][$type] . "</td>";
			}

			// 대여일 소계
			foreach (['유료', '교육', '지원', '할인', '유료+지원'] as $type) {
				$EXCEL_TXT .= "<td style='background-color:#f5f5f5; font-weight:bold;'>" . $statistics[$month]['소계']['days'][$type] . "</td>";
			}

			// 이용인원 소계
			foreach (['유료', '교육', '지원', '할인', '유료+지원'] as $type) {
				$EXCEL_TXT .= "<td style='background-color:#f5f5f5; font-weight:bold;'>" . $statistics[$month]['소계']['people'][$type] . "</td>";
			}

			$EXCEL_TXT .= "</tr>";

			// 월별 합계
			$EXCEL_TXT .= "
	        <tr>
	            <td colspan='3' style='background-color:#eee; font-weight:bold;'>합</td>
	            <td colspan='5' style='background-color:#eee; font-weight:bold;'>" . (
						$statistics[$month]['소계']['counts']['유료'] +
						$statistics[$month]['소계']['counts']['교육'] +
						$statistics[$month]['소계']['counts']['지원'] +
						$statistics[$month]['소계']['counts']['할인'] +
						$statistics[$month]['소계']['counts']['유료+지원']
					) . "건</td>
	            <td colspan='5' style='background-color:#eee; font-weight:bold;'>" . (
						$statistics[$month]['소계']['days']['유료'] +
						$statistics[$month]['소계']['days']['교육'] +
						$statistics[$month]['소계']['days']['지원'] +
						$statistics[$month]['소계']['days']['할인'] +
						$statistics[$month]['소계']['days']['유료+지원']
					) . "일</td>
	            <td colspan='5' style='background-color:#eee; font-weight:bold;'>" . (
						$statistics[$month]['소계']['people']['유료'] +
						$statistics[$month]['소계']['people']['교육'] +
						$statistics[$month]['소계']['people']['지원'] +
						$statistics[$month]['소계']['people']['할인'] +
						$statistics[$month]['소계']['people']['유료+지원']
					) . "명</td>
	        </tr>";
		}
	}
}

// 연간 총계
if (isset($statistics['연간총계'])) {
	$EXCEL_TXT .= "
    <tr>
        <td colspan='3' style='background-color:#e0e0e0; font-weight:bold;'>{$year}년 총합</td>
        <td colspan='5' style='background-color:#e0e0e0; font-weight:bold;'>" . (
			$statistics['연간총계']['counts']['유료'] +
			$statistics['연간총계']['counts']['교육'] +
			$statistics['연간총계']['counts']['지원'] +
			$statistics['연간총계']['counts']['할인'] +
			$statistics['연간총계']['counts']['유료+지원']
		) . "건</td>
        <td colspan='5' style='background-color:#e0e0e0; font-weight:bold;'>" . (
			$statistics['연간총계']['days']['유료'] +
			$statistics['연간총계']['days']['교육'] +
			$statistics['연간총계']['days']['지원'] +
			$statistics['연간총계']['days']['할인'] +
			$statistics['연간총계']['days']['유료+지원']
		) . "일</td>
        <td colspan='5' style='background-color:#e0e0e0; font-weight:bold;'>" . (
			$statistics['연간총계']['people']['유료'] +
			$statistics['연간총계']['people']['교육'] +
			$statistics['연간총계']['people']['지원'] +
			$statistics['연간총계']['people']['할인'] +
			$statistics['연간총계']['people']['유료+지원']
		) . "명</td>
    </tr>";
}

$EXCEL_TXT .= "</table>";

echo $EXCEL_TXT;

SetDisConn($dblink);
?>
