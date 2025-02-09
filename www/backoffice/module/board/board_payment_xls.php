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

$boardTypeMap = [
    'edu' => '교육',
    'equ' => '장비',
    'place' => '공간'
];

$filename = $_SITE['NAME'] . "_결제내역_" . date('mdHi') . ".xls";
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
    <th>접수번호</th>
    <th>이메일(아이디)</th>
    <th>이름</th>
    <th>연락처</th>
    <th>구분</th>
    <th>교육명/장비명/공간명</th>
    <th>결제금액</th>
    <th>할인</th>
    <th>결제상태(결제일)</th>
    <th>환불현황(환불완료일)</th>
    <th>영수증</th>
    <th>비고</th>
    <th>관리</th>
";

for ($i = 0; $i < $arrList["total"]; $i++) {
    $categories = explode('|', $arrList["list"][$i]['category']);
    $categoryNames = array_map(function($categoryId) use ($arrAllCategory) {
        return htmlspecialchars($arrAllCategory[$categoryId], ENT_QUOTES, 'UTF-8');
    }, $categories);
    $categoryString = implode(', ', $categoryNames);

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
    <td>" . htmlspecialchars($arrList["list"][$i]['app_no'], ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($arrList["list"][$i]['w_user'], ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($arrList["list"][$i]['name'], ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars('="' . $arrList["list"][$i]['tel'] . '"', ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($boardTypeMap[$arrList["list"][$i]['board_type']], ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($arrList["list"][$i]['subject'], ENT_QUOTES, 'UTF-8') . "</td>
    <td>" . htmlspecialchars($arrList["list"][$i]['finalamount'], ENT_QUOTES, 'UTF-8') . "원</td>
    <td>" . (!empty($arrList["list"][$i]['discount']) ? 'Y' : 'N') . "</td>
    <td>" . htmlspecialchars($arrList["list"][$i]['deposit_status'], ENT_QUOTES, 'UTF-8') .
        ($arrList["list"][$i]['deposit_status'] != '입금대기' ? " (" . htmlspecialchars(date("Y-m-d", strtotime($arrList["list"][$i]['deposit_date'])), ENT_QUOTES, 'UTF-8') . ")" : "") . "</td>
    <td>" . ($arrList["list"][$i]['refund_status'] == "환불완료" ? htmlspecialchars($arrList["list"][$i]['refund_status'], ENT_QUOTES, 'UTF-8') . " (" . htmlspecialchars(date("Y-m-d", strtotime($arrList["list"][$i]['refund_complete_date'])), ENT_QUOTES, 'UTF-8') . ")" : "") . "</td>
    <td></td>
    <td>" . htmlspecialchars($arrList["list"][$i]['contents'], ENT_QUOTES, 'UTF-8') . "</td>
</tr>
    ";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?><?php
