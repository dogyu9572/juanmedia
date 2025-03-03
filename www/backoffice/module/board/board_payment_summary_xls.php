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

$boardTypeMap = [
    'edu' => '교육',
    'equ' => '장비',
    'place' => '공간'
];

$filename = $_SITE['NAME'] . "_매출내역 관리_" . date('mdHi') . ".xls";
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
    <th>No.</th>
    <td>구분</td>
    <td>접수번호</td>
    <td>증빙구분</td>
    <th>금액</th>
    <th>이름</th>
    <th>세부사항</th>
    <th>매출일</th>
    <th>수납구분</th>
</tr>
";

for ($i = 0; $i < $arrList["list"]["total"]; $i++) {
    $EXCEL_TXT .= "
    <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . htmlspecialchars($boardTypeMap[$arrList["list"][$i]['board_type']], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . (htmlspecialchars($arrList["list"][$i]['app_no'], ENT_QUOTES, 'UTF-8') ? "'" . htmlspecialchars($arrList["list"][$i]['app_no'], ENT_QUOTES, 'UTF-8') : '') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['proof_type'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['finalamount'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['name'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['subject'], ENT_QUOTES, 'UTF-8') . "</td>        
        <td>" . htmlspecialchars(date("Y-m-d", strtotime($arrList["list"][$i]['deposit_date'])), ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['deposit_status'], ENT_QUOTES, 'UTF-8') . "</td>              
    </tr>
    ";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>