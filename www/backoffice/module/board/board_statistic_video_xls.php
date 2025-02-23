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

// 통계 데이터 가져오기
$statistics = getVideoStatistics($year);

$filename = $_SITE['NAME'] . "_상영회통계_" . date('YmdHi') . ".xls";
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
        <td colspan='3' style='text-align:center; font-weight:bold;'>{$year}년 상영회 통계</td>
    </tr>
    <tr>
        <td>월</td>
        <td>건수</td>
        <td>상영회인원수</td>
    </tr>";

foreach ($statistics as $month => $data) {
    $EXCEL_TXT .= "
    <tr>
        <td>{$month}</td>
        <td>" . number_format($data['count']) . "</td>
        <td>" . number_format($data['people']) . "</td>
    </tr>";
}

$EXCEL_TXT .= "</table>";

echo $EXCEL_TXT;

SetDisConn($dblink);
?>