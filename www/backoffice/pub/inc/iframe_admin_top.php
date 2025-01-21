<?PHP
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/module/admin/admin.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/whereis.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrMenuList = getAdminMenu();
for($i=0;$i<$arrMenuList["total"];$i++){
	$arrayMyMenu[] = $arrMenuList["list"][$i]['m_code'];
	$arrayMenuList[$arrMenuList["list"][$i]['m_code']] = $arrMenuList["list"][$i]['m_name'];
}

$arrBoradInfoList = getArticleList($_conf_tbl["board_info"], 0, 0, "order by boardname ASC ");

$arrCategoryInfoList = getArticleList("tbl_category_info", 0, 0, "order by idx ASC ");

for($i=0;$i<$arrBoradInfoList["total"];$i++){
	$arrBoardInfoAuth[$arrBoradInfoList["list"][$i]['boardid']] = $arrBoradInfoList["list"][$i]['boardname'];
}

for($i=0;$i<$arrCategoryInfoList["total"];$i++){
	$arrCategoryInfoAuth[$arrCategoryInfoList["list"][$i]['categoryid']] = $arrCategoryInfoList["list"][$i]['categoryname'];
}

$arrAdminInfo = getAdminInfo($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]);

//DB해제
SetDisConn($dblink);

######################################################## 디자인 ST
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/dtd.php";
?>