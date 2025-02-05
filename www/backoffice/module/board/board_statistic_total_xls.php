<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("login");
	jsHistory("-1");
endif;

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
	$tables = ['edu', 'edu_applicants', 'equ', 'equ_applicants', 'place', 'place_applicants'];

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

$filename = $_SITE['NAME'] . "_전체통계_" . date('YmdHi') . ".xls";
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
    <td colspan='4' style='text-align:center; font-weight:bold;'>{$year}년 통계</td>
</tr>
<tr>
    <td>월</td>
    <td>구분</td>
    <td>항목</td>
    <td>합계</td>
</tr>
";

foreach ($activeMonths as $i) {
	$EXCEL_TXT .= "
    <tr>
        <td rowspan='6'>{$i}월</td>
        <td rowspan='2'>교육</td>
        <td>프로그램 수(개)</td>
        <td>" . getCount($monthlyCounts, 'edu', $i) . "</td>
    </tr>
    <tr>
        <td>참여인원수(명)</td>
        <td>" . getCount($monthlyCounts, 'edu_applicants', $i) . "</td>
    </tr>
    <tr>
        <td rowspan='2'>장비대여</td>
        <td>대여건 수(일)</td>
        <td>" . getCount($monthlyCounts, 'equ', $i) . "</td>
    </tr>
    <tr>
        <td>장비이용자 수(명)</td>
        <td>" . getCount($monthlyCounts, 'equ_applicants', $i) . "</td>
    </tr>
    <tr>
        <td rowspan='2'>공간대여</td>
        <td>대여건 수(일)</td>
        <td>" . getCount($monthlyCounts, 'place', $i) . "</td>
    </tr>
    <tr>
        <td>공간이용자 수(명)</td>
        <td>" . getCount($monthlyCounts, 'place_applicants', $i) . "</td>
    </tr>
    ";
}

$EXCEL_TXT .= "
<tr>
    <td rowspan='2'>총합</td>
    <td>교육연인원+장비이용자수+공간이용자수+기타이용자</td>
    <td>이용자(명)</td>
    <td>{$monthlyCounts['total_users']}</td>
</tr>
<tr>
    <td>교육프로그램수+기타프로그램</td>
    <td>프로그램 수(개)</td>
    <td>{$monthlyCounts['total_programs']}</td>
</tr>
</table>
";

echo $EXCEL_TXT;

SetDisConn($dblink);
?>