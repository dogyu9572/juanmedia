<?
include ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/banner/banner.lib.php");

//DB����
$dblink = SetConn($_conf_db["main_db"]);

$arrList = setBannerCount(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx]));

//DB����
SetDisConn($dblink);

if($arrList==true){
	echo "1";
}else{
	echo "0";
}
?>