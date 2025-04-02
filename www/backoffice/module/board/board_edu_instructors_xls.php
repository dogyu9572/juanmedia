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

// 페이지 상단에 배열 정의 추가
$arrCategory01 = [
	115 => '상설',
	116 => '공동체',
	37 => '미디어 체험'
];

$scale=0;
$arrList = getBoardListBaseNFile($_GET['boardid'], $_GET["category"], $_GET['sw'], $_GET['sk'], $_GET['page_size'], $_GET['offset'],'', "admin");

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

for ($i = 0; $i < $arrList["list"]["total"]; $i++) {
	// 새로운 카테고리 처리 방식 적용
	$categories = explode('|', $arrList["list"][$i]['category']);
	$categoryNames = [];
	foreach ($categories as $category) {
		// 정의된 배열에 있는 경우만 추가
		if(isset($arrCategory01[$category])) {
			$categoryNames[] = htmlspecialchars($arrCategory01[$category], ENT_QUOTES, 'UTF-8');
		}
	}
	$categoryString = implode(', ', $categoryNames);

	$EXCEL_TXT .= "
 <tr>
  <td>" . ($i + 1) . "</td>
  <td>" . $categoryString . "</td>
  <td>" . htmlspecialchars($arrList["list"][$i]['name'], ENT_QUOTES, 'UTF-8') . "</td>
  <td>'" . htmlspecialchars($arrList["list"][$i]['tel'], ENT_QUOTES, 'UTF-8') . "</td>
  <td>" . htmlspecialchars($arrList["list"][$i]['email'], ENT_QUOTES, 'UTF-8') . "</td>
  <td>" . htmlspecialchars(substr($arrList["list"][$i]['wdate'], 0, 10), ENT_QUOTES, 'UTF-8') . "</td>
 </tr>
 ";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>