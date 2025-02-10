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

$arrLevel = getArticleList ( $_conf_tbl ["member_level"], 0, 0, "order by level_no desc " );
for($i = 0; $i < $arrLevel["total"]; $i ++) {
    $arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}

$filename = $_SITE['NAME'] . "_미디어체험신청리스트_" . date('mdHi') . ".xls";
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
    <td>No.</td>
    <td>이름</td>
    <td>연락처</td>
    <td>이메일</td>
    <td>체험분야</td>
    <td>단체명(학교명)</td>
    <td>주소</td>
    <td>인원</td>
    <td>희망날짜</td>
    <td>희망 이용시간</td>
    <td>신청일</td>
    <td>비고</td>
</tr>
";

for ($i = 0; $i < $arrList["list"]["total"]; $i++) {
    $EXCEL_TXT .= "
    <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['name'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars('="' . $arrList["list"][$i]['tel'] . '"', ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['email'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars(str_replace('|', ',', $arrList["list"][$i]['experience']), ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['group_name'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['address'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['total_members'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars(date("Y-m-d", strtotime($arrList["list"][$i]['desired_date'])), ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['start_hour'] . ':' . $arrList["list"][$i]['start_minute'] . ' ~ ' . $arrList["list"][$i]['end_hour'] . ':' . $arrList["list"][$i]['end_minute'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars(date("Y-m-d", strtotime($arrList["list"][$i]['wdate'])), ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($arrList["list"][$i]['contents'], ENT_QUOTES, 'UTF-8') . "</td>
    </tr>
    ";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;
SetDisConn($dblink);
?><?php
