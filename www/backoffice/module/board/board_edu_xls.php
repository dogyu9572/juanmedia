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

//DB
$dblink = SetConn($_conf_db["main_db"]);

$scale=0;
$arrList = getBoardListBaseNFile($_GET['boardid'], $_GET["category"], $_GET['sw'], $_GET['sk'], $_GET['page_size'], $_GET['offset'],'', "admin");

$arrAllCategory = getCategoryAll();

$filename = $_SITE['NAME'] . "_교육목록_" . date('mdHi') . ".xls";
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
    <td>No</td>
    <td>카테고리</td>
    <td>상태</td>
    <td>교육명</td>
    <td>교육기간</td>
    <td>요일</td>
    <td>시간</td>
    <td>신청완료</td>
    <td>대기</td>
    <td>정원</td>
    <td>수강료</td>
    <td>등록일</td>
</tr>";

for ($i = 0; $i < $arrList["total"]; $i++) {
    $dayTypeMap = [
        'weekly' => '매주',
        'biweekly' => '격주',
        'other' => '기타'
    ];
    $dayType = $dayTypeMap[$arrList["list"][$i]['day_type']];
    $days = str_replace('|', '/', $arrList["list"][$i]['days']);

    $EXCEL_TXT .= "
    <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . htmlspecialchars(getCategoryName($arrList["list"][$i]['category1']), ENT_QUOTES, 'UTF-8') . " / " . htmlspecialchars(getCategoryName($arrList["list"][$i]['category2']), ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['reception_status'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['subject'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars(substr($arrList["list"][$i]['r_start_date'], 0, 10), ENT_QUOTES, 'UTF-8') . " ~ " . htmlspecialchars(substr($arrList["list"][$i]['r_end_date'], 0, 10), ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . $dayType . "<br/>" . $days . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['start_hour'], ENT_QUOTES, 'UTF-8') . ":" . htmlspecialchars($arrList["list"][$i]['start_minute'], ENT_QUOTES, 'UTF-8') . " ~ " . htmlspecialchars($arrList["list"][$i]['end_hour'], ENT_QUOTES, 'UTF-8') . ":" . htmlspecialchars($arrList["list"][$i]['end_minute'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['complete_count'], ENT_QUOTES, 'UTF-8') . "명</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['waitlist'], ENT_QUOTES, 'UTF-8') . "명</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['capacity'], ENT_QUOTES, 'UTF-8') . "명</td>
        <td>" . number_format($arrList["list"][$i]['fee']) . "원</td>
        <td>" . htmlspecialchars(substr($arrList["list"][$i]['wdate'], 0, 10), ENT_QUOTES, 'UTF-8') . "</td>
    </tr>";
}

$EXCEL_TXT .= "</table>";

echo $EXCEL_TXT;

SetDisConn($dblink);
?><?php
