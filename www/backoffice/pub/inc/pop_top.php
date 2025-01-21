<?PHP
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/module/admin/admin.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/whereis.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrMenuList = getAdminMenu();
for($i=0;$i<$arrMenuList["total"];$i++){
	$arrayMyMenu[] = $arrMenuList["list"][$i]['m_code'];
	$arrayMenuList[$arrMenuList["list"][$i]['m_code']] = $arrMenuList["list"][$i]['m_name'];
}

//DB해제
SetDisConn($dblink);

######################################################## 디자인 ST
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/dtd.php";
?>
<style type="text/css">
.container{
	margin-top:0;padding:10px 30px 30px 30px;
}
.over_tbl .bdr_list {min-width: 888px;}
</style>