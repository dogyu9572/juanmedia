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

$filename = $_SITE['NAME'] . "_강사관리_" . date('mdHi') . ".xls";
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
   <td>분야</td>
   <td>이름</td>
   <td>연락처</td>
   <td>이메일</td>
   <td>등록일</td>
</tr>
";

for ($i = 0; $i < $arrList["total"]; $i++) {
    $categories = explode('|', $arrList["list"][$i]['category']);
    $categoryNames = array_map(function($categoryId) use ($arrAllCategory) {
        return htmlspecialchars($arrAllCategory[$categoryId], ENT_QUOTES, 'UTF-8');
    }, $categories);
    $categoryString = implode(', ', $categoryNames);
    $EXCEL_TXT .= "
 <tr>
  <td>" . ($i + 1) . "</td>
  <td>" . $categoryString . "</td>
  <td>" . htmlspecialchars($arrList["list"][$i]['name'], ENT_QUOTES, 'UTF-8') . "</td>
  <td>" . htmlspecialchars('="' . $arrList["list"][$i]['tel'] . '"', ENT_QUOTES, 'UTF-8') . "</td>
  <td>" . htmlspecialchars($arrList["list"][$i]['email'], ENT_QUOTES, 'UTF-8') . "</td>
  <td>" . htmlspecialchars(substr($arrList["list"][$i]['wdate'], 0, 10), ENT_QUOTES, 'UTF-8') . "</td>
 </tr>
 ";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>