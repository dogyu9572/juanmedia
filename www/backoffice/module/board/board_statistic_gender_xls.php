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
$statistics = getAgeGenderStatistics($year);

function getCount($statistics, $range, $gender) {
	$total = 0;
	foreach ($statistics as $table => $data) {
		if (isset($data[$range][$gender])) {
			$total += $data[$range][$gender];
		}
	}
	return $total;
}

$filename = $_SITE['NAME'] . "_성별/나이통계_" . date('YmdHi') . ".xls";
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
    <td colspan='5' style='text-align:center; font-weight:bold;'>{$year}년 통계</td>
</tr>
<tr>
    <td>구분</td>
    <td>연령</td>
    <td>전체</td>
    <td>남자</td>
    <td>여자</td>
</tr>
";

$ageRanges = [
	'0~4', '5~9', '10~14', '15~19', '20~24', '25~29', '30~34', '35~39', '40~44', '45~49', '50~54', '55~59', '60~64', '65~69', '70~74', '75~79', '80세 이상', '미집계'
];

foreach ($ageRanges as $range) {
	$total = getCount($statistics, $range, '남자') + getCount($statistics, $range, '여자');
	$male = getCount($statistics, $range, '남자');
	$female = getCount($statistics, $range, '여자');

	$EXCEL_TXT .= "
    <tr>
        <td>연령</td>
        <td>{$range}</td>
        <td>{$total}</td>
        <td>{$male}</td>
        <td>{$female}</td>
    </tr>
    ";
}

$totalUsers = array_sum(array_map(function($range) use ($statistics) {
	return getCount($statistics, $range, '남자') + getCount($statistics, $range, '여자');
}, $ageRanges));

$totalMale = array_sum(array_map(function($range) use ($statistics) {
	return getCount($statistics, $range, '남자');
}, $ageRanges));

$totalFemale = array_sum(array_map(function($range) use ($statistics) {
	return getCount($statistics, $range, '여자');
}, $ageRanges));

$EXCEL_TXT .= "
<tr>
    <td colspan='2'>총합</td>
    <td>{$totalUsers}</td>
    <td>{$totalMale}</td>
    <td>{$totalFemale}</td>
</tr>
</table>
";

echo $EXCEL_TXT;

SetDisConn($dblink);
?>