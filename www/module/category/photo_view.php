<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//제품정보
$arrInfo = getCatalogFileInfo2(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

//DB해제
SetDisConn($dblink);

if($arrInfo["total"] < 1){
	jsMsg("해당 카테고리의 이미지를 찾을 수 없습니다.");
	selfClose();
}

//_DEBUG($arrInfo);
?>
<html>
<head>
<title><?=$arrInfo["list"][0]["ori_name"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin=0 topmargin=0 onContextMenu='return false;'>
<center>
<a href='javascript:window.close(self)'><img src='/uploaded/category/<?=$arrInfo["list"][0]["re_name"]?>' border='0'></a>
</center>
</body>
</html>