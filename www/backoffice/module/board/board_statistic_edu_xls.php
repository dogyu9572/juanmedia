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
        <td colspan='7' style='text-align:center; font-weight:bold;'>{$year}년 교육 통계</td>
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
		foreach ($statistics[$month] as $category1 => $subcategories) {
			foreach ($subcategories as $category2 => $data) {
				$EXCEL_TXT .= "
                <tr>
                    <td>{$month}월</td>
                    <td>" . getCategoryName($category1) . "</td>
                    <td>" . getCategoryName($category2) . "</td>
                    <td>{$data['edu_count']}</td>
                    <td>{$data['applicants_count']}</td>
                    <td>{$data['certificates_count']}</td>
                    <td>" . number_format($data['completion_rate']) . "%</td>
                    <td>" . number_format($data['total_amount']) . "</td>
                </tr>";
			}
		}
	}
}

$EXCEL_TXT .= "</table>";

echo $EXCEL_TXT;

SetDisConn($dblink);
?>
