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
$arrList = getXlsList($_GET['boardid'],  $_GET['sw'], $_GET['sk'], "", 0);

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
    <td>상태</td>
    <td>상영회명</td>
    <td>접수기간</td>
    <td>상영일</td>
    <td>정원</td>
    <td>대기</td>
    <td>등록일</td>
";

for ($i = 0; $i < $arrList["total"]; $i++) {

    $EXCEL_TXT .= "
    <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['reception_status'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['subject'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['r_start_date'], ENT_QUOTES, 'UTF-8') . " ~ " . htmlspecialchars($arrList["list"][$i]['r_end_date'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['screening_date'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['capacity'], ENT_QUOTES, 'UTF-8') . "명</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['waitlist'], ENT_QUOTES, 'UTF-8') . "명</td>
        <td>" . htmlspecialchars(substr($arrList["list"][$i]['wdate'], 0, 10), ENT_QUOTES, 'UTF-8') . "</td>        
    </tr>
    ";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?><?php
