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
    <th>메뉴구분</th>
    <th>구분</th>
    <th>제목</th>
    <th>발송제목</th>
    <th>작성자</th>
    <th>등록일</th>
</tr>";

// 카테고리 정의 재사용
$arrCategory = array(
    "sms" => "SMS",
    "kakao" => "알림톡",
    "email" => "EMAIL"
);

$arrEtc_3 = array(
    "edu" => "교육",
    "equ" => "장비",
    "place" => "공간",
);

for ($i = 0; $i < $arrList["list"]["total"]; $i++) {
    $EXCEL_TXT .= "
    <tr>
        <td>" . ($arrList["list"][$i]['no'] == "0" ? "공지" : ($arrList["total"] - $i - (int)$_GET['offset'])) . "</td>
        <td>" . htmlspecialchars($arrEtc_3[$arrList["list"][$i]['etc_3']], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrCategory[$arrList["list"][$i]['category']], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['subject'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['etc_4'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['name'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['wdate'], ENT_QUOTES, 'UTF-8') . "</td>
    </tr>";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?><?php
